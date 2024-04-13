{{-- <div class="container mt-5" style="width: 600px; margin: auto; background: gray: padding: 15px">
    <h3>student submissions</h3>
    <p>Student {{ $name }} with email account  {{ $email }} submitted his contribution.</p>
    <img width="100%" src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fkenh14.vn%2Fcung-ngam-loat-hinh-anh-dep-nhat-ve-da-dang-van-hoa-dai-dien-viet-nam-noi-bat-voi-trau-dong-lua-nuoc-qua-doi-than-thuoc-20200718002356247.chn&psig=AOvVaw2uuMO-sw7y4FEK1ebgsvHa&ust=1712731574886000&source=images&cd=vfe&opi=89978449&ved=0CBIQjRxqFwoTCNjRqrrEtIUDFQAAAAAdAAAAABAE" alt="">
</div> --}}
<div class="container mt-5" style="max-width: 600px; margin: auto; background: #f9f9f9; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
    <h3 style="text-align: center; color: #007bff;">Notification</h3>
    <p style="text-align: center; font-size: 14px;">{{ $name }} ({{ $email }}) Contribution submission has been completed successfully.</p>
    {{-- <p style="text-align: center; font-size: 18px;">Contribution submission has been completed successfully.</p> --}}
    <p style="text-align: center; font-size: 18px; color: red; animation: blink 1s infinite;">Note that the comment period for this article is only 14 days starting from today</p>
    <style>
        @keyframes blink {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
    <div style="text-align: center;">
        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e" alt="Bài nộp thành công" style="max-width: 100%; border-radius: 10px; margin-top: 20px;">
    </div>
</div>
