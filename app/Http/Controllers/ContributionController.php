<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Event;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

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
        foreach ($contributions as $contribution) {
            $contribution->submitted_on = Carbon::parse($contribution->submitted_on);
        }
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
        // email
        $email = auth()->user()->email;
        $name = auth()->user()->name;
        // $email = 'hoangcvgch190446@fpt.edu.vn';
        Mail::to('tuananh17042001aa@gmail.com')->send(new SendMail($email,$name));
        return redirect()->route('contributions.index')->with('success', 'Contribution created successfully');

        // Redirect đến trang tạo mới với thông báo thành công
        return redirect()->route('contributions.create')->with('success', 'Contribution created successfully');

    }

    //update store new khong su dung đuọc
    // public function store(Request $request)
    // {
    //     // Validate other fields
    //     $validatedData = $request->validate([
    //         'user_id' => 'required',
    //         'faculty_id' => 'required',
    //         'title' => 'required|string|max:50',
    //         'description' => 'required|string',
    //         'file.*' => 'required|mimes:doc,docx,pdf,jpg,jpeg,png', // Chú ý vào 'file.*' để kiểm tra từng file trong mảng
    //         'event_id' => 'required|integer',
    //     ]);

    //     // Kiểm tra xem event có quá hạn không
    //     $event = Event::findOrFail($request->event_id);
    //     if ($event->final_date < now()) {
    //         // Nếu đã quá hạn, không cho phép nộp contribution và chuyển hướng về trang trước với thông báo
    //         return redirect()->back()->with('error', 'The event is overdue. Cannot submit contribution.');
    //     }

    //     // Tiếp tục xử lý nếu event chưa quá hạn

    //     $fileNames = []; // Khởi tạo một mảng để lưu trữ các tên tệp tin
        
    //     // Kiểm tra xem có tệp nào được tải lên không
    //     if ($request->hasFile('file')) {
    //         $files = $request->file('file');
            
    //         foreach ($files as $file) {
    //             // Tạo tên file mới với timestamp và đuôi mở rộng
    //             $fileName = time() . '_' . $file->getClientOriginalName();

    //             // Lưu trữ file trong thư mục storage/app/public/uploads
    //             $file->move(public_path('storage/uploads'), $fileName);

    //             // Thêm tên file vào mảng fileNames
    //             $fileNames[] = $fileName;
    //         }
    //     }
        
    //     // Gán một chuỗi của các tên tệp tin cho trường file trong $validatedData
    //     $validatedData['file'] = implode(',', $fileNames);

    //     // Set submitted_on field to current time
    //     $validatedData['submitted_on'] = now();

    //     // Tạo một Contribution mới và truyền dữ liệu vào
    //     Contribution::create($validatedData);

    //     // Redirect đến trang tạo mới với thông báo thành công
    //     return redirect()->route('contributions.create')->with('success', 'Contribution created successfully');
    
    // }


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

    //comment
    public function comment(Contribution $contribution){
        return view('admin.contribution.comment', compact('contribution'));
    }
    
    //comment store
    public function submitComment(Request $request, Contribution $contribution){
        // Kiểm tra xem người dùng hiện tại có phải là Marketing Coordinator không
        // if (!$request->user()->isMarketingCoordinator()) {
        //     return back()->with('error', 'Bạn không có quyền thực hiện hành động này');
        // }

        // Kiểm tra xem nội dung comment có được cung cấp không
        $request->validate([
            'content' => 'required|string'
        ]);
        
        // Tạo comment mới
        $comment = new Comment();
        $comment->contribution_id = $contribution->id;
        $comment->user_id = $request->user()->id;
        
        $comment->content = $request->input('content');
        $comment->commented_at = now();
        $comment->save();
        
        return back()->with('success', 'Comment Succesfully');
    }

    //comment view
    public function viewComment($id){
        // $comments = Comment::find($id);

        $comments = Comment::where('contribution_id', $id)->get();

        return view('admin.contribution.cmview', compact('comments'));
    }

    public function contact(){
        // $email = 'hoangcvgch190446@fpt.edu.vn';
        // Mail::to($email)->send(new SendMail());

        return view('admin.email.contact');
    }

    // public function senMail(){
    //     $email = 'hoangcvgch190446@fpt.edu.vn';
    //     Mail::to($email)->send(new SendMail());
    //     return redirect()->route('contributions.index')->with('success', 'Contribution created successfully');
    // }
}