<?php

namespace App\Http\Controllers;

use App\Models\PublicApi;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class PublicApiController extends Controller
{
        
    /**
     * Get Data paginated and get data count
     * index
     *
     * @return View
     */
    public function index() : View
    {
        //
        $apidata = PublicApi::paginate("20");
        $apidataCount = PublicApi::count();
        return view('public-api.index',compact('apidata','apidataCount'));
    }

    
        
    /**
     * importApiData
     *
     * @param  mixed $request
     * @return void
     */
    public function importApiData(Request $request) : RedirectResponse
    {
        //
        $request->validate([
            'url' => 'required|url'
        ]);

        $response = Http::get($request->url);

        if ($response->ok()) {
            $apiData = $response->json();

            if (isset($apiData['entries']) && is_array($apiData['entries'])) {
                $newDataCreated = false;
                $chunkedEntries = array_chunk($apiData['entries'], 400);

                foreach ($chunkedEntries as $entries) {
                    foreach ($entries as $entry) {
                        $uniqueString = $entry['API'] . $entry['Description'] . $entry['Auth'] . $entry['Link'] . $entry['HTTPS'] . $entry['Cors'] . $entry['Category'];
                        $uniqueKey = hash('sha256', $uniqueString);

                        try {
                            // Attempt to upsert the data using the generated unique key
                            $saveline = PublicApi::firstOrCreate(
                                ['unique_id' => $uniqueKey], 
                                [
                                    'api' => $entry['API'],
                                    'description' => $entry['Description'],
                                    'auth' => $entry['Auth'],
                                    'https' => $entry['HTTPS'],
                                    'cors' => $entry['Cors'],
                                    'link' => $entry['Link'],
                                    'category' => $entry['Category'],
                                    'unique_id' => $uniqueKey 
                                ]
                            );

                            if($saveline->wasRecentlyCreated) $newDataCreated = true;

                        } catch (\Exception $e) {
                            Log::error("Error importing API data: " . $e->getMessage());
                            return redirect()->back()->with('error', 'An error occurred while importing the API data.');
                        }
                    }
                }
                if( $newDataCreated ) {
                    return redirect()->back()->with('success', 'Public Api imported successfully.');
                } else {
                    return redirect()->back()->with('success', 'No new data imported.');
                }
                
            }

        }
        return redirect()->back()->with('error', 'An error occurred while importing the API data.');

    }
    


    public function test()
    {
        $response = Http::get("https://api.publicapis.org/entries");

        if ($response->ok()) {
            $apiData = $response->json();

            if (isset($apiData['entries']) && is_array($apiData['entries'])) {
                $duplicates = $this->findDuplicates($apiData['entries'], 'Description');

                return response()->json($duplicates); // Return the duplicates
            }
        }
    }
    public function findDuplicates(array $entries, string $key)
    {
        $uniqueKeys = [];
        $duplicates = [];

        foreach ($entries as $entry) {
            $uniqueKey = $entry[$key] ?? null;

            if ($uniqueKey !== null) {
                if (in_array($uniqueKey, $uniqueKeys)) {
                    $duplicates[] = $entry;
                } else {
                    $uniqueKeys[] = $uniqueKey;
                }
            }
        }

        return $duplicates;
    }



    /**
     * Remove the specified resource from storage.
     */
    public function truncate()
    {
        //
        if (PublicApi::truncate()) {
            return redirect()->back()->with('success', 'Public Api deleted successfully.');
        } else {
            return redirect()->back()->with('errer', 'Issue occured while deleting data');
        }

    }
}
