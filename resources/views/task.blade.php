<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-200 w-3/6 flex flex-col items-center mx-auto h-screen">
    <div class="container h-44 mt-10 shadow-lg flex flex-col items-center justify-center bg-white p-6 rounded-lg">
        <h1 class="text-3xl font-bold ">{{ $task->title }}</h1>
        <p class="mb-4 text-sm text-gray-400">Dibuat Pada: {{ $task->created_at->format('d-m-Y') }}</p>
        <button onclick="document.getElementById('modal').classList.remove('hidden')"
            class="bg-zinc-900 py-2 px-6 rounded-full text-white font-bold transition-transform duration-00 hover:scale-105">Buat
            Tugas</button>
    </div>

    <div class="bg-white shadow-lg w-full py-5 mt-10 flex flex-col px-8 pt-4 rounded-lg">
        <div class="flex justify-between items-center mb-4 border-b-2 border-gray-300 pb-2">
            <h2 class="font-bold text-2xl">Daftar Tugas</h2>
        </div>
            <!-- Cek jika subtasks ada, jika tidak tampilkan pesan -->
    @if($task->subtasks->isEmpty())
    <div class="flex items-center justify-center h-32">
        <p class="text-md font-semibold text-center text-gray-400">Tidak Ada Tugas</p>
    </div>
    @else
        @foreach ($task->subtasks as $subtask)
            <div class="bg-zinc-900 h-32 rounded-lg flex justify-between items-center pl-7 pr-10 mb-4">
                <div class="flex items-center gap-7">
                    <form action="{{ route('subtasks.toggleStatus', $subtask->id) }}" method="POST">
                        @csrf
                        @method('POST') <!-- Menggunakan POST karena kita akan toggle status -->
                        <input type="checkbox" name="status"
                            class="appearance-none w-4 h-4 rounded-full border-2 border-white checked:bg-white checked:border-white focus:outline-none focus:ring-white"
                            {{ $subtask->status === 'done' ? 'checked' : '' }} onchange="this.form.submit()" />
                        <!-- Submit form saat checkbox diubah -->
                    </form>

                    <div class="flex flex-col justify-center rounded-lg ">
                        <h2
                            class="font-bold text-2xl text-white {{ $subtask->status === 'done' ? 'line-through text-gray-600' : '' }}">
                            {{ $subtask->title }}
                        </h2>
                        <p class="text-white {{ $subtask->status === 'done' ? 'line-through text-gray-600' : '' }}">
                            Dibuat pada: {{ $subtask->deadline ? $subtask->deadline->format('d-m-Y') : '-' }}
                        </p>
                        @php
                            $warnaPrioritas =
                                $subtask->status === 'done'
                                    ? 'bg-gray-600 text-zinc-900'
                                    : match ($subtask->priority) {
                                        'tinggi' => 'bg-red-600',
                                        'sedang' => 'bg-yellow-400',
                                        'rendah' => 'bg-green-500',
                                    };
                        @endphp

                        <div
                            class="{{ $warnaPrioritas }} w-24 mt-2 rounded-full flex justify-center items-center text-white">
                            <p class=" font-semibold">{{ $subtask->priority }}</p>
                        </div>
                    </div>
                </div>
                <div class="gap-2 flex ">
                    <button
                        onclick="openSubtaskEditModal('{{ $subtask->id }}', '{{ $subtask->title }}', '{{ $subtask->deadline }}', '{{ $subtask->priority }}')"
                        class="bg-white text-zinc-900 font-bold py-2 px-6 rounded-md">
                        Edit
                    </button>
                    <button onclick="openDeleteModal('{{ $subtask->id }}')"
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
            <form action="{{ route('subtasks.store', ['task' => $task->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task->id }}">
                <input type="hidden" name="status" value="on_progress">

                <div class="mb-4">
                    <label class="block text-md font-bold ">Judul</label>
                    <input type="text" name="title" required
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2" placeholder="Masukkan judul">
                </div>

                <div class="mb-4">
                    <label class="block text-md font-bold ">Tenggat Waktu</label>
                    <input type="date" name="deadline" required
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2" placeholder="Masukkan judul">
                </div>

                <div class="mb-4">
                    <label class="block text-md font-bold ">Prioritas</label>
                    <select name="priority" name="priority"
                        class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option>Pilih Prioritas</option>
                        <option value="tinggi">Tinggi</option>
                        <option value="sedang">Sedang</option>
                        <option value="rendah">Rendah</option>
                    </select>
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
    <div id="subtaskEditModal"
        class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-center">
                <h2 class="text-2xl font-bold">Edit Subtugas</h2>
            </div>
            <form id="subtaskEditForm" method="POST" action="{{ route('subtasks.update', $task->id) }}">
                @csrf
                @method('PUT')

                <div class="mt-4">
                    <label class="block font-bold">Judul</label>
                    <input type="text" name="title" id="subtaskEditTitle"
                        class="w-full p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mt-4">
                    <label class="block font-bold">Tenggat Waktu</label>
                    <input type="date" name="deadline" id="subtaskEditDeadline"
                        class="w-full p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mt-4">
                    <label class="block font-bold">Prioritas</label>
                    <select name="priority" id="subtaskEditPriority" class="w-full p-2 border border-gray-300 rounded"
                        required>
                        <option value="rendah">Rendah</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeSubtaskEditModal()"
                        class="text-white bg-zinc-900 font-bold py-2 px-5 rounded-md">Batal</button>
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
        function openSubtaskEditModal(id, title, deadline, priority) {
            const form = document.getElementById('subtaskEditForm');
            const modal = document.getElementById('subtaskEditModal');

            // Atur action form
            form.action = `/subtasks/${id}`;

            // Isi data field
            document.getElementById('subtaskEditTitle').value = title;
            document.getElementById('subtaskEditDeadline').value = deadline;
            document.getElementById('subtaskEditPriority').value = priority;

            // Tampilkan modal
            modal.classList.remove('hidden');
        }

        function closeSubtaskEditModal() {
            document.getElementById('subtaskEditModal').classList.add('hidden');
        }

        // Fungsi untuk membuka modal dan mengatur form action
        function openDeleteModal(subtaskId) {
            const formAction = `/subtasks/${subtaskId}`; // pastikan format URL benar
            document.getElementById('deleteForm').action = formAction; // Atur action form
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // Fungsi untuk menutup modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</body>

</html>
