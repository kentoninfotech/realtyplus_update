<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;

class DocumentController extends Controller
{
    /**
     * This controller will handle
     * Show documents,
     * create, update and delete document.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all document.
     */
    public function index()
    {
        //
    }
    /**
     * Display a documents of the property and units.
     *
     */
    public function propertyDocument($id)
    {
        $property = Property::findOrFail($id);
        $documents = $property->documents;
        // $documents = PropertyTask::where('property_id', $property->id)->paginate(10);
        return view('properties.property-documents', compact('documents','property'));
    }

}
