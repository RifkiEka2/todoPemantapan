<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-200 w-3/6 flex flex-col items-center mx-auto h-screen">
    @if ($tasks->isEmpty())
        <div class="flex items-center w-full justify-center h-screen">
            <div class="w-full shadow-lg flex flex-col items-center justify-center bg-white p-6 rounded-lg">
                <h1 class="text-3xl font-bold">Selamat Datang di Aplikasi To-Do List</h1>
                <p class="mb-4 text-center w-5/6">Kelola tugas dan matpel dengan mudah dan efisien.</p>
                <button onclick="document.getElementById('modal').classList.remove('hidden')"
                    class="bg-zinc-900 py-2 px-6 rounded-full text-white font-bold transition-transform duration-200 hover:scale-105">
                    Buat Mata Pelajaran
                </button>
            </div>
        </div>
    @else
        <div
            class="sticky top-10 z-10 container h-44 mt-10 shadow-lg flex flex-col items-center justify-center bg-white p-6 rounded-lg">
            <h1 class="text-3xl font-bold">Selamat Datang di Aplikasi To-Do List</h1>
            <p class="mb-4 text-center w-5/6">Kelola tugas dan matpel dengan mudah dan efisien.</p>
            <button onclick="document.getElementById('modal').classList.remove('hidden')"
                class="bg-zinc-900 py-2 px-6 rounded-full text-white font-bold transition-transform duration-200 hover:scale-105">
                Buat Mata Pelajaran
            </button>
        </div>

        <div class="bg-white shadow-lg w-full py-5 mt-10 flex flex-col px-8 pt-4  rounded-lg">
            <div class="flex justify-between items-center mb-4 border-b-2 border-gray-300 pb-2">
                <h2 class="font-bold text-2xl">Daftar Mata Pelajaran</h2>
            </div>


            @foreach ($tasks as $task)
                <div
                    class="relative bg-zinc-800 hover:bg-zinc-900 h-32 rounded-lg flex justify-between items-center pl-10 mb-4 transition-transform duration-500 hover:scale-105">
                    <a href="{{ route('tasks.show', $task->id) }}" class="w-full h-full flex items-center">
                        <div class=" flex flex-col justify-center w-full h-32 rounded-lg ">
                            <h2 class="font-bold text-2xl text-white">{{ $task->title }}</h2>
                            <p class="text-white">Dibuat pada: {{ $task->created_at->format('d-m-Y ') }}</p>
                        </div>
                    </a>
                    <div class="absolute right-10 top-1/2 -translate-y-1/2 gap-2 flex ">
                        <button onclick="openEditModal('{{ $task->id }}', '{{ $task->title }}')"
                            class="bg-white text-zinc-900 font-bold py-2 px-6 rounded-md">Edit</button>
                        <button onclick="openDeleteModal('{{ $task->id }}', '{{ $task->title }}')"
                            class="bg-white text-zinc-900 font-bold py-2 px-4 rounded-md">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md ">
            <!-- Judul Modal -->
            <div class="flex justify-center">
                <h2 class="text-2xl font-bold mb-2">Tambah Tugas</h2>
            </div>

            <!-- Form -->
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xl font-bold ">Judul</label>
                    <input type="text" name="title" required
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2" placeholder="Masukkan judul">
                </div>

                <!-- Tombol aksi -->
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal').classList.add('hidden')"
                        class="text-white bg-zinc-900 font-bold py-2 px-5 rounded-md">Batal</button>
                    <button class="text-white bg-zinc-900 font-bold py-2 px-4 rounded-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-center">
                <h2 class="text-2xl font-bold">Edit Tugas</h2>
            </div>
            <form id="editForm" action="{{ route('tasks.update', 'task_id') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mt-4">
                    <label for="title" class="block font-bold">Judul</label>
                    <input type="text" name="title" id="editTitle"
                        class="w-full p-2 border border-gray-300 rounded" value="" required>
                </div>

                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="text-white bg-zinc-900 font-bold py-2 px-5 rounded-md">Kembali</button>
                    <button class="text-white bg-zinc-900 font-bold py-2 px-4 rounded-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete -->
    <div id="deleteModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-center mb-5">
                <h2 class="text-2xl font-bold">Yakin Ingin Menghapus Tugas??</h2>
            </div>
            <div class="flex justify-center space-x-2">
                <button type="button" onclick="closeDeleteModal()"
                    class="text-white bg-zinc-900 font-bold py-2 px-5 rounded-md">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="text-white bg-zinc-900 font-bold py-2 px-4 rounded-md">Hapus</button>
                </form>
            </div>
        </div>
    </div>


    <script>
        function openEditModal(taskId, taskTitle) {
            // Set form action ke URL edit task
            const formAction = `/tasks/${taskId}`;
            document.getElementById('editForm').action = formAction;

            // Isi field title dengan nilai task title
            document.getElementById('editTitle').value = taskTitle;

            // Tampilkan modal edit
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            // Menyembunyikan modal
            document.getElementById('editModal').classList.add('hidden');
        }

        function openDeleteModal(taskId, taskTitle) {
            const formAction = `/tasks/${taskId}`;
            document.getElementById('deleteForm').action = formAction;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>

</body>

</html>
