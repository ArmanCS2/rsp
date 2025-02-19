<?php


use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $receipt = Receipt::latest()->first();
    $apiKey="AIzaSyA7vimj75Wfv2ffqLcqv91pLaQ2nG7wyyE";
    $res=Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey",[
        'contents' => [
            ['parts' => [
                ['text' => 'جزئیات دقیق این رسید انتقال پول را به من بده به صورت یک متن به این ترتیب کد پیگیری یا شماره پیگیری یا شماره بازیابی و مبلغ به ریال و تاریخ به شمسی به صورت حروف انگلیسی در خروجی فقط متن را نشان بده که قابلیت explode شدن توسط php را داشته باشد بدون هیچ توضیح اضافی و فقط حروف انگلیسی و فرمت خروجی همیشه به این صورت باشد مبلغ باید به ریال باشد: 9999999999,99999999,9999/99/99'],
                ['inline_data' => [
                    'mime_type' => $receipt->mime_type,
                    'data' => $receipt->file_base64
                ]]
            ]]
        ]
    ]);
    $text=$res->json()['candidates'][0]['content']['parts'][0]['text'];
    $text=trim($text);
    //dd(explode(",", $text));
    return view('welcome',compact('text'));
})->name('home');

Route::post('upload-file',function (Request $request){
    $realFile = $request->file;
    $file=base64_encode(file_get_contents($request->file));
    \App\Models\Receipt::create([
        'file_base64'=>$file,
        'mime_type'=>$realFile->getMimeType(),
    ]);
    return redirect()->route('home');
})->name('upload-file');



