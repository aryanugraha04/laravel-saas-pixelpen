<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Slider;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use App\Models\Heading;

class HomeController extends Controller
{
    public function HomeSlider()
    {
        $slider = Slider::find(1);
        return view('admin.backend.slider.get_slider', compact('slider'));
    }
    // End Method

    public function UpdateSlider(Request $request) 
    {
        $slider_id = $request->id;

        // Cari slider berdasarkan ID. findOrFail akan otomatis error jika tidak ditemukan.
        $slider = Slider::findOrFail($slider_id);

        // Update HANYA data teks. Tidak ada lagi logika gambar.
        $slider->update([
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
        ]);

        // Siapkan notifikasi sukses
        $notification = [
            'message' => 'Slider Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }
    // End Method

    public function AllHeading()
    {
        $heading = Heading::latest()->get();
        return view('admin.backend.heading.all_heading', compact('heading'));
    }
    // End Method

    public function AddHeading()
    {
        return view('admin.backend.heading.add_heading');
    }
    // End Method

    public function StoreHeading(Request $request)
    {

        Heading::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $notification = [
            'message' => 'Heading Created Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.heading')->with($notification);
    }
    // End Method

    public function EditHeading($id)
    {
        $heading = Heading::find($id);
        return view('admin.backend.heading.edit_heading', compact('heading'));
    }
    // End Method

    public function UpdateHeading(Request $request)
    {
        $heading_id = $request->id;

        // Cari slider berdasarkan ID. findOrFail akan otomatis error jika tidak ditemukan.
        $heading = Heading::findOrFail($heading_id);

        // Update HANYA data teks. Tidak ada lagi logika gambar.
        $heading->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Siapkan notifikasi sukses
        $notification = [
            'message' => 'Heading Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.heading')->with($notification);
    }
    // End Method

    public function DeleteHeading($id)
    {
        $heading = Heading::find($id);
        $heading->delete();

        $notification = [
            'message' => 'Heading Deleted Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }
    // End Method

    public function UseCase()
    {
        return view('home.page.use_case');
    }
    // End Method

    public function Features()
    {
        return view('home.page.features');
    }
    // End Method

    public function Pricing()
    {
        return view('home.page.pricing');
    }
    // End Method

    public function Contact()
    {
        return view('home.page.contact');
    }
    // End Method

    public function StoreContact(Request $request){
        
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

    $notification = array(
        'message' => 'Message Sent Successfully',
        'alert-type' => 'success'
    ); 
    return redirect()->back()->with($notification); 

    }
   //End Method 

    public function ContactMessage()
    {
        $contact = Contact::orderBy('id', 'desc')->get();
        return view('admin.backend.contact.all_contact', compact('contact'));
    }
    // End Method

    public function DeleteContactMessage($id)
    {
        Contact::find($id)->delete();
        $notification = array(
            'message' => 'Contact Message Deleted Successfully',
            'alert-type' => 'success'
        ); 
        return redirect()->back()->with($notification);
    }
    // End Method
}
