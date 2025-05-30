<?php

namespace App\Http\Controllers;

use App\Models\SifcoHelper;
use App\Models\TransitDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransitDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'transit_id' => 'required|integer',
            'file_name' => 'required|string|max:255',
            'file_url' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,txt|max:8048'
        ]);

       $save_document = new TransitDocument();

       $save_document->transit_id = $request->transit_id;

       $save_document->user_id = Auth::id();

       $save_document->file_name = $request->file_name;

       $save_document->file_url = SifcoHelper::uploadImage($request->file('file_url'));

       $save_document->save();

       return redirect()->route('containers.show',$save_document->transit_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransitDocument $transitDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransitDocument $transitDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransitDocument $transitDocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransitDocument $transitDocument)
    {
        // TransitDocument::destroy($transitDocument->id);

        // return back()->with('success', 'File deleted successfully.');
        try {
            // Delete the file from storage
            Storage::disk('public')->delete('documents/' . $transitDocument->file_url);
    
            // Delete the record from the database
            $transitDocument->delete();
    
            return back()->with('success', 'Document deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the document.');
        }
    }
}
