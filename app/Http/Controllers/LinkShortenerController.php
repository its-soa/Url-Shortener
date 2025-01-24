<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ShortUrl;

class LinkShortenerController extends Controller
{
    public $storedUrl = [];
    public $baseUrl = "http://short.est/";


    public function encode(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        // Input Validation
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Invalid URL');
        }

        // I store the url the user inputs
        $originalUrl = $request->input('url');
        // return $originalUrl;

        // Afterwards, I create a short code (first 6 chars of hash) and store it.
        $shortCode = substr(md5($originalUrl), 0, 6);
        // return $shortCode;

        // Check if the URL already exists in the database
        $encodedUrl = ShortUrl::where('original_url', $originalUrl)->first();
        if ($encodedUrl) {
            return redirect()->back()
                ->with('original_url', $originalUrl)
                ->with('short_url', $this->baseUrl . $encodedUrl->short_code);
        }


        // Save to new record the database if it doesn't exsist
        ShortUrl::create([
            'short_code' => $shortCode,
            'original_url' => $originalUrl
        ]);

        return redirect()->back()
            ->with('original_url', $originalUrl)
            ->with('short_url', $this->baseUrl . $shortCode);
    }

    public function decode(Request $request)
    {

        $shortUrl = $request->short_url;
        $shortCode = str_replace($this->baseUrl, '', $shortUrl); // Extracting the short code here

        $urlRecord = ShortUrl::where('short_code', $shortCode)->first();

        // Returns redirect with error if code doesn't exist
        if (!$urlRecord) {
            return redirect()->back()
                ->with('error', 'Opps! Short URL Not Found')
                ->withInput();
        }

        return redirect()->back()
            ->with('decoded_url', $urlRecord->original_url);
    }

    // This can be tested using Postman
    public function encodeAPI(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        // Input Validation
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid URL',
                'details' => $validator->errors()
            ], 400);
        }

        // I store the url the user inputs
        $originalUrl = $request->input('url');
        // return $originalUrl;

        // Afterwards, I create a short code (first 6 chars of hash) and store it.
        $shortCode = substr(md5($originalUrl), 0, 6);
        // return $shortCode;

        // Check if the URL already exists in the database
        $encodedUrl = ShortUrl::where('original_url', $originalUrl)->first();
        if ($encodedUrl) {
            return response()->json([
                'original_url' => $originalUrl,
                'short_url' => $this->baseUrl . $encodedUrl->short_code
            ]);
        }

        // Save to new record the database if it doesn't exsist
        ShortUrl::create([
            'short_code' => $shortCode,
            'original_url' => $originalUrl
        ]);

        return response()->json([
            'original_url' => $originalUrl,
            'short_url' => $this->baseUrl . $shortCode
        ]);
    }

    // This can be tested using Postman
    public function decodeAPI(Request $request)
    {

        $shortUrl = $request->short_url;
        $shortCode = str_replace($this->baseUrl, '', $shortUrl); // Extracting the short code here

        $urlRecord = ShortUrl::where('short_code', $shortCode)->first();

        // Returns 404 is code doesn't exsist.
        if (!$urlRecord) {
            return response()->json(['error' => 'Opps! Short URL Not Found'], 404);
        }

        return response()->json([
            'original_url' => $urlRecord->original_url
        ]);
    }
}