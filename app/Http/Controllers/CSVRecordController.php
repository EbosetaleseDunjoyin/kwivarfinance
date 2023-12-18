<?php

namespace App\Http\Controllers;

use App\Models\CsvRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;


class CsvRecordController extends Controller
{
       
    /**
     * index
     *
     * @return View
     */
    public function index() : View
    {
        //

        $CsvRecordsData = CsvRecord::where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'DESC')
        ->get();

        $perPage = 10;
       $csvRecords = $CsvRecordsData->groupBy('file_name')->map(function ($records) use ($perPage) {
            $currentPage = Paginator::resolveCurrentPage('page');
            
            $total = $records->count();
            $startIndex = ($currentPage - 1) * $perPage;
            $sliced = $records->slice($startIndex, $perPage)->values();

            $paginatedData = new LengthAwarePaginator(
                $sliced,
                $total,
                $perPage,
                $currentPage,
                [
                    'path' => Paginator::resolveCurrentPath(),
                    'pageName' => 'page',
                ]
            );

            return $paginatedData;
        });

        // return response()->json($csvRecords);
        // exit;

        return view("csv-records.index" , compact('csvRecords'));

    }

     
    /**
     * importCSV
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function importCSV(Request $request) : RedirectResponse
    {
        //
        $request->validate([
            'file' => 'required|mimes:csv|max:2048',
        ]);

        $file = $request->file('file');
        $fileContents = file($file->getPathname());

        // Skip the first line (header) by removing it from the array
        $header = array_shift($fileContents);

        foreach ($fileContents as $lineNumber => $line) {
            $data = str_getcsv($line);

            // Validate the rows
            // if (count($data) < 5) {
            //     return redirect()->back()->with('error', "Invalid data in row $lineNumber. Please check the CSV file format.");
            // }

            try {
                CsvRecord::firstOrCreate(
                    [
                        'csv_id' => $data[0],
                        'email' => $data[1],
                        'name' => $data[2],
                        'phone' => $data[3],
                        'address' => $data[4],
                        'user_id' => auth()->user()->id,
                    ],
                    [
                        'file_name' => $file->getClientOriginalName(),
                    ]
                );
            } catch (\Exception $e) {
                
                Log::error("Error importing row $lineNumber: " . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while importing the CSV file.');
            }
        }

        return redirect()->back()->with('success', 'CSV file imported successfully.');
    }

       
    /**
     * Delete a particular csv file
     * destroy
     *
     * @param  mixed $filename
     * @return RedirectResponse
     */
    public function destroy($filename): RedirectResponse
    {
        //
        try {
            $destroyData = CsvRecord::where('user_id', auth()->user()->id)
                    ->where('file_name', $filename)
                    ->delete();

        } catch (\Exception $e) {

            Log::error("Error deleting data: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the CSV data.');
        }

        
        return redirect()->back()->with('success', 'CSV file deleted successfully.');
        
    }
}
