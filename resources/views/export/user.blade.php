<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    @vite('resources/js/export.js');
    <title>Export Model</title>
</head>

<body>
    <div class="px-0 lg:px-12 py-16 md:w-full md:flex items-stretch">
        <div class="sidenav text-center px-1 w-full md:w-2/12">
            <div class="border-separate border border-slate-400 p-2 rounded-sm">
                <div class="text-gray-700 font-semibold text-2xl">Export</div>

                <div class="m-2 mt-5">
                    <a class="block py-2 px-3 bg-[#34aeff] hover:bg-[#26a8ff] active:bg-[#2191db] text-white cursor-pointer font-sans font-semibold shadow-md rounded-sm select-none" {{realRoute('export.sheet', 'href')}}>Sheet</a>
                </div>

                <div class="m-2">
                    <a class="block py-2 px-3 bg-[#34aeff] hover:bg-[#26a8ff] active:bg-[#2191db] text-white cursor-pointer font-sans font-semibold shadow-md rounded-sm select-none" {{realRoute('export.multi-sheet', 'href')}}>Multi sheet</a>
                </div>
            </div>

            <div class="border-separate border border-slate-400 p-2 rounded-sm mt-5">
                <div class="text-gray-700 font-semibold text-2xl truncate">Notification</div>

                <div class="m-2 mt-5">
                    <a class="block py-2 px-3 bg-[#34aeff] hover:bg-[#26a8ff] active:bg-[#2191db] text-white cursor-pointer font-sans font-semibold shadow-md rounded-sm select-none" id="getAgentToken-btn">Receive notifications</a>
                </div>
            </div>
        </div>

        <div class="text-center pt-4 px-1 md:pt-0 border-test md:flex-initial md:w-10/12">
            <table class="table-auto border-separate border border-slate-400 text-gray-700">
                <thead>
                    <tr>
                        <th class="border border-slate-300">ID</th>
                        <th class="border border-slate-300">Tên</th>
                        <th class="border border-slate-300">Email</th>
                        <th class="border border-slate-300">Đường dẫn ảnh</th>
                        <th class="border border-slate-300">ID mạng xã hội</th>
                        <th class="border border-slate-300">Tên mạng xã hội</th>
                        <th class="border border-slate-300">Xác thực email</th>
                        <th class="border border-slate-300">Trạng thái</th>
                        <th class="border border-slate-300">Mã đăng nhập</th>
                        <th class="border border-slate-300">Tạo lúc</th>
                        <th class="border border-slate-300">Cập nhật lúc</th>
                    </tr>
                </thead>

                <tbody>
                    @isset($users)
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->image }}</td>
                                <td>{{ $user->social_id }}</td>
                                <td>{{ $user->social_id }}</td>
                                <td>{{ $user->email_verified_at }}</td>
                                <td>{{ $user->status }}</td>
                                <td>{{ $user->remember_token }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->updated_at }}</td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>

    
</body>

</html>
