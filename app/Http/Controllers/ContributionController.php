<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contributions = Contribution::all();
        return view('admin.contribution.view', compact('contributions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.contribution.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function store(Request $request)
    // {
    //     // Validate other fields
    //     $validatedData = $request->validate([
    //         'user_id' => 'required',
    //         'faculty_id' => 'required',
    //         'title' => 'required|string|max:50',
    //         'description' => 'required|string',
    //         'file' => 'required|mimes:doc,docx,pdf,jpg,jpeg,png',
    //         'event_id' => 'required|integer',
    //     ]);

    //     // if ($request->hasFile('file')) {
    //     //     $file = $request->file('file')->getClientOriginalName();
    //     //     $request->file('file')->move(public_path('uploads'), $file);
    //     // }

    //     if ($request->hasFile('file')) {
    //         $file = $request->file('file');
    
    //         // Tạo tên file mới với timestamp và đuôi mở rộng
    //         $fileName = time() . '.' . $file->getClientOriginalExtension();
    
    //         // Lưu trữ file trong thư mục uploads
    //         $file->move(public_path('storage'), $fileName);
    
    //         // Cập nhật mảng validatedData với đường dẫn file
    //         $validatedData['file'] = $fileName;
    //         // $validatedData['file'] = 'app/public/uploads/' . $fileName;
    //     }

    //     // Set submitted_on field to current time
    //     $validatedData['submitted_on'] = Carbon::now();

    //     Contribution::create($validatedData);
    //     return redirect()->route('contributions.create')->with('success', 'Contribution created successfully');
    // }

    //có thể upload nhiều file ở đây
    public function store(Request $request)
    {
        // Validate other fields
        $validatedData = $request->validate([
            'user_id' => 'required',
            'faculty_id' => 'required',
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'file.*' => 'required|mimes:doc,docx,pdf,jpg,jpeg,png', // Chú ý vào 'file.*' để kiểm tra từng file trong mảng
            'event_id' => 'required|integer',
        ]);

        $fileNames = []; // Khởi tạo một mảng để lưu trữ các tên tệp tin
        
        // Kiểm tra xem có tệp nào được tải lên không
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            
            foreach ($files as $file) {
                // Tạo tên file mới với timestamp và đuôi mở rộng
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Lưu trữ file trong thư mục storage/app/public/uploads
                $file->move(public_path('storage/uploads'), $fileName);

                // Thêm tên file vào mảng fileNames
                $fileNames[] = $fileName;
            }
        }
        
        // Gán một chuỗi của các tên tệp tin cho trường file trong $validatedData
        $validatedData['file'] = implode(',', $fileNames);

        // Set submitted_on field to current time
        $validatedData['submitted_on'] = now();

        // Tạo một Contribution mới và truyền dữ liệu vào
        Contribution::create($validatedData);

        // Redirect đến trang tạo mới với thông báo thành công
        return redirect()->route('contributions.create')->with('success', 'Contribution created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $contributions = Contribution::find($id);
    //     $contributions->delete();
    //     return redirect()->route('contributions.index')->with('success', 'Delete successfully');
    // }

    public function destroy($id)
    {
        try {
            // Tìm đối tượng Contribution cần xóa
            $contribution = Contribution::findOrFail($id);

            // Xóa đối tượng Contribution
            $contribution->delete();

            // Sau khi xóa đối tượng Contribution, cũng xóa tập tin tương ứng từ thư mục storage
            $filePath = public_path('storage/' . $contribution->file);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $filePath = public_path('my-zip-file.zip/' . $contribution->file);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            return redirect()->route('contributions.index')->with('success', 'Delete successfully');
        } 
        catch (\Exception $e) 
        {
            return redirect()->route('contributions.index')->with('error', $e->getMessage());
        }
    }

    // public function download(Contribution $contribution){
    //     try {
    //         $filePath = $contribution->file;
    //         $fullFilePath = public_path('storage' . $filePath);
    
    //         if (!file_exists($fullFilePath)) {
    //             return response()->json(['error' => 'File không tồn tại'], 404);
    //         }
    
    //         $zip = new ZipArchive();
    //         $fileName = "my-zip-file.zip";

    //         // $files = File::files($fullFilePath);
    //         //     $filteredFiles = array_filter($files, function($file) use ($contribution) {
    //         //         return basename($file) === $contribution->file;
    //         //     });

    //         if($zip->open($fileName, ZipArchive::CREATE)){
    //             $files = File::files($fullFilePath);
    //             // $filteredFiles = array_filter($files, function($file) use ($contribution) {
    //             //     return basename($file) === $contribution->file;
    //             // });
    //             foreach($files as $file){
    //                 $nameInZipFile = basename($file);
    //                 $zip->addFile($file, $nameInZipFile);
    //             }
    //             $zip->close();
    //         }

    //         return response()->download($fileName);
    //     } catch (\Exception $e){
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }


    // public function download($id) {
    //     try {
    //         // Tìm đối tượng Contribution
    //         $contributions = Contribution::findOrFail($id);
            
    //         // Tạo tên tập tin ZIP
    //         $zipFileName = 'contributions_' . $contributions->user->name . '_' . now()->format('YmdHis') . '.zip';
    //         $zipFilePath = public_path($zipFileName);
            
    //         // Khởi tạo đối tượng ZipArchive
    //         $zip = new ZipArchive();
    //         if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
    //             // Thêm tệp đã tải lên vào tập tin ZIP
    //             $filePath = public_path('storage/' . $contributions->file);
    //             if (File::exists($filePath)) {
    //                 $zip->addFile($filePath, $contributions->file);
    //             }
    //             $zip->close();
    //         }
    
    //         // Trả về tệp ZIP cho người dùng
    //         return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    //DOWLOAN MOI UPDATE

    public function download($id)
    {
        try {
            // Tìm đối tượng Contribution
            $contribution = Contribution::findOrFail($id);
            
            // Tạo tên tập tin ZIP
            $zipFileName = 'contributions_' . $contribution->user->name . '_' . now()->format('YmdHis') . '.zip';
            $zipFilePath = public_path($zipFileName);
            
            // Khởi tạo đối tượng ZipArchive
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                // Thêm các tệp đã tải lên vào tập tin ZIP
                $fileNames = explode(',', $contribution->file);
                foreach ($fileNames as $fileName) {
                    $filePath = public_path('storage/uploads/' . $fileName);
                    if (File::exists($filePath)) {
                        $zip->addFile($filePath, $fileName);
                    }
                }
                $zip->close();
            }

            // Trả về tệp ZIP cho người dùng
            return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }
}