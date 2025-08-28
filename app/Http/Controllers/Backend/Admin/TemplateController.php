<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Template;
use App\Models\TemplateInputFields;
use App\Models\GeneratedContent;
use OpenAI;
use App\Models\User;


class TemplateController extends Controller
{
    public function AdminTemplate()
    {
        $templates = Template::latest()->get();
        return view('admin.backend.template.all_template', compact('templates'));
    }
    // End Method

    public function AddTemplate()
    {
        return view('admin.backend.template.add_template');
    }
    //End Method

    public function StoreTemplate(Request $request)
    {
        /// Validate the request data
        $validateData = $request->validate([
            'title' => 'required|string|max:225',
            'description' => 'required|string|',
            'category' => 'required|string',
            'icon' => 'required|string',
            'prompt' => 'required|string',
            'is_active' => 'required|in:0,1',
            'input_fields' => 'required|array|size:1',
            'input_fields.*.title' => 'required|string|max:225',
            'input_fields.*.description' => 'required|string',
            'input_fields.*.type' => 'required|in:text,textarea',
        ]);

        //Create the Template
        $template = Template::create([
            'title' => $validateData['title'],
            'description' => $validateData['description'],
            'category' => $validateData['category'],
            'icon' => $validateData['icon'],
            'prompt' => $validateData['prompt'],
            'is_active' => $validateData['is_active'],
            'created_by' => Auth::id(),
        ]);

        // Save Data in Input Field Table
        $inputField = $validateData['input_fields'][0];
        TemplateInputFields::create([
            'template_id' => $template->id,
            'title' => $inputField['title'],
            'description' => $inputField['description'],
            'type' => $inputField['type'],
            'is_required' => true,
            'order' => 0,
        ]);

        $notification = array(
            'message' => 'Template Created Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('admin.template')->with($notification);
    }
    //End Method

    public function EditTemplate($id)
    {
        $template = Template::with('inputFields')->findOrFail($id);
        return view('admin.backend.template.edit_template', compact('template'));
    }
    // End Method

    public function UpdateTemplate(Request $request, $id)
    {
        /// Validate the request data
        $validateData = $request->validate([
            'title' => 'required|string|max:225',
            'description' => 'required|string|',
            'category' => 'required|string',
            'icon' => 'required|string',
            'prompt' => 'required|string',
            'is_active' => 'required|in:0,1',
            'input_fields' => 'required|array|size:1',
            'input_fields.*.title' => 'required|string|max:225',
            'input_fields.*.description' => 'required|string',
            'input_fields.*.type' => 'required|in:text,textarea',
        ]);

        //Update the Template
        $template = Template::findOrFail($id);
        $template->update([
            'title' => $validateData['title'],
            'description' => $validateData['description'],
            'category' => $validateData['category'],
            'icon' => $validateData['icon'],
            'prompt' => $validateData['prompt'],
            'is_active' => $validateData['is_active'],
        ]);

        // Save Data in Input Field Table
        $inputField = $validateData['input_fields'][0];
        $templateInputField = TemplateInputFields::where('template_id', $template->id)->first();

        if ($templateInputField){
            $templateInputField->title = $inputField['title'];
            $templateInputField->description = $inputField['description'];
            $templateInputField->type = $inputField['type'];
            $templateInputField->is_required = true;
            $templateInputField->order = 0;
            $templateInputField->save();
        }

        $notification = array(
            'message' => 'Template Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('admin.template')->with($notification);
    }
    //End Method

    public function DetailsTemplate($id)
    {
        $template = Template::with('inputFields')->findOrFail($id);
        $user = Auth::user();
        return view('admin.backend.template.details_template', compact('template', 'user'));
    }
    // End Method

    public function AdminContentGenerate(Request $request, $id)
    {
        // Fetch the template with its input fields
        $template = Template::with('inputFields')->findOrFail($id);
        $user = Auth::user();
        
        /// Validate request 
        $validateData = $request->validate([
            'language' => 'required|string|in:English (USA),Indonesia,French (France),Hindi (India),Spanish (Spain)',
            'ai_model' => 'required|string|in:llama-3.3-70B-Versatile,openai/gpt-oss-120b',
            'result_length' => 'required|integer|min:50|max:1000',
        ]);
        
        /// Validate Dynamic Input Fields
        foreach($template->inputFields as $field)
        {
            $fieldName = str_replace(' ', '_', $field->title);
            $request->validate([
                $fieldName => 'required|string',
            ]); 
        }
    
    // Get user input for dynamic fields
    $inputData = $request->except(['_token', 'language', 'ai_model', 'result_length']);
    Log::info('Input data', ['inputData' => $inputData]); // Debug input data
    
    $prompt = $template->prompt;

    /// Replace placeholder with user input
    foreach($template->inputFields as $field)
    {
        $fieldName = str_replace(' ', '_', $field->title);
        $fieldValue = $inputData[$fieldName] ?? '';
        $prompt = str_replace('{' . str_replace(' ', '_', $field->title) . '}', $fieldValue, $prompt);
        $prompt = str_replace('{' . $field->title .  '}', $fieldValue, $prompt);
    }

    /// Replace result_length placeholder
    $prompt = str_replace('{result_length}', $validateData['result_length'], $prompt);

    $prompt = "In {$validateData['language']}, {$prompt} Aim for approximately {$validateData['result_length']} words.";
    Log::info('Final Prompt', ['prompt' => $prompt]); // Debug prompt

    /// Check word limit
    $estimatedWordCount = $validateData['result_length'];
    if ($user-> words_used + $estimatedWordCount > $user->current_words_usage)
    {
        return response()->json([
            'success' => false,
            'message' => 'Word limit exceeded', 
        ],400);
    }

    try {
        //Generate content with AI

        // 1. Buat client API untuk Groq secara manual di dalam controller
        $groqClient = OpenAI::factory()
            ->withApiKey(config('services.groq.api_key')) // Mengambil API key dari config
            ->withBaseUri('https://api.groq.com/openai/v1')   // Mengarahkan ke alamat server Groq
            ->make();

        // 2. Lakukan panggilan API menggunakan client yang baru dibuat
        $response = $groqClient->chat()->create([
            'model' => $validateData['ai_model'], // Nama model AI yang dipilih
            'messages' => [
                ['role' => 'user', 'content' => $prompt], // Prompt yang dikirim ke AI
            ],
        ]);

        $output = $response->choices[0]->message->content;
        $wordCount = str_word_count($output);

        /// Update user word usage
        $user->words_used += $wordCount;
        User::where('id', $user->id)->update(['words_used' => $user->words_used]);

        // Save data to generated content table
        GeneratedContent::create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'input' => json_encode($inputData),
            'output' => $output,
            'word_count' => $wordCount,
        ]);
    
        return response()->json([
            'success' => true,
            'output' => $output,
        ]);

    } catch (\Exception $e) {
        // Catat error untuk debugging
        Log::error('Groq API Call Failed: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to generate content: ' . $e->getMessage(),
        ], 500);
    }
    }
    // End Method
}
