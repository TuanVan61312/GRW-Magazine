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
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
                
        $user = Auth::user();

        if ($user->isAdmin()|| $user->isMarketingManager()) {
            $contributions = Contribution::all();
        } elseif ($user->isMarketingCoordination() || $user->isGuest()) {
            $userFaculty = $user->faculty;
            $contributions = Contribution::whereIn('user_id', function($query) use ($userFaculty) {
                $query->select('id')->from('users')->where('faculty_id', $userFaculty->id);
            })->get();
        } elseif ($user->isStudent()) {
            $contributions = Contribution::where('user_id', $user->id)->get();
        }

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
            'file.*' => 'required|mimes:doc,docx,pdf,jpg,jpeg,png', 
            'event_id' => 'required|integer',
        ]);

        $fileNames = []; //Initialize an array to store file names
        
        // Check if any files are uploaded
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            
            foreach ($files as $file) {
                // Create a new file name with timestamp and extension
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Store files in the storage/app/public/uploads directory
                $file->move(public_path('storage/uploads'), $fileName);

                // Add filenames to the fileNames array
                $fileNames[] = $fileName;
            }
        }
        
        // Assigns a string of filenames to the file field in $validatedData
        $validatedData['file'] = implode(',', $fileNames);

        // Set submitted_on field to current time
        $validatedData['submitted_on'] = now();

        // Create a new Contribution and pass in the data
        Contribution::create($validatedData);
        // email
        $email = auth()->user()->email;
        $name = auth()->user()->name;
        // $email = 'hoangcvgch190446@fpt.edu.vn';
        Mail::to('hoangcvgch190446@fpt.edu.vn')->send(new SendMail($email,$name));
        return redirect()->route('contributions.index')->with('success', 'Contribution created successfully');

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

        // Check if comment content is provided
        $request->validate([
            'content' => 'required|string'
        ]);
        
        // create new commnet
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
}