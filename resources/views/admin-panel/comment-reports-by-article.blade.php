<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Comment Reports</title>
        <meta name="csrf-token"
              content="{{ csrf_token() }}">
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>

    <body class="font-lexend bg-[#f4f4f4]">

        <div id="app"
             class="flex min-h-screen">
            <!-- Sidebar -->
            @include('partials.admin-sidebar')

            <!-- Main Content -->
            <div id="mainContent"
                 class="ml-64 flex flex-1 flex-col">
                <!-- Header -->
                <header class="flex items-center justify-between bg-white p-4 shadow">
                    <h1 class="text-xl font-bold">Comment Reports</h1>
                    <div>
                        <span class="text-gray-700">Admin</span>
                    </div>
                </header>

                <!-- Page Content -->
                <div class="p-6">
                    <h1 class="mb-6 text-2xl font-bold">
                        Comment Reports for: {{ $article->title }}
                    </h1>

                    @if ($reports->isEmpty())
                        <p class="text-gray-600">No reports for this article.</p>
                    @else
                        <!-- Tabs -->
                        <div class="mb-6 flex space-x-4 border-b">
                            <button class="tab-btn border-b-2 border-yellow-600 px-4 py-2 text-yellow-600"
                                    data-target="pending-reports">Pending</button>
                            <button class="tab-btn px-4 py-2 text-green-600"
                                    data-target="resolved-reports">Resolved</button>
                            <button class="tab-btn px-4 py-2 text-red-600"
                                    data-target="dismissed-reports">Dismissed</button>
                        </div>

                        <!-- Pending Reports -->
                        <div id="pending-reports"
                             class="tab-content">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-gray-100 text-left">
                                        <th class="border p-2">Comment</th>
                                        <th class="border p-2">Comment Author</th>
                                        <th class="border p-2">Reported By</th>
                                        <th class="border p-2">Reason</th>
                                        <th class="border p-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reports->where('status', 'pending') as $report)
                                        <tr id="report-{{ $report->id }}"
                                            class="border-b">
                                            <td class="border p-2">{{ $report->comment->content }}</td>
                                            <td class="border p-2">{{ $report->comment->user->name }}</td>
                                            <td class="border p-2">{{ $report->user->name }}</td>
                                            <td class="border p-2">
                                                <strong>{{ ucfirst($report->reason) }}</strong><br>
                                                <small>{{ $report->additional_info }}</small>
                                            </td>
                                            <td class="border p-2">
                                                <form action="{{ route('admin.comment-reports.update', $report) }}"
                                                      method="POST"
                                                      data-ajax
                                                      data-report-id="{{ $report->id }}"
                                                      class="inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden"
                                                           name="status"
                                                           value="dismissed">
                                                    <button type="submit"
                                                            class="rounded bg-red-500 px-3 py-1 text-white">Dismiss</button>
                                                </form>
                                                <form action="{{ route('admin.comment-reports.update', $report) }}"
                                                      method="POST"
                                                      data-ajax
                                                      data-report-id="{{ $report->id }}"
                                                      class="ml-1 inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden"
                                                           name="status"
                                                           value="resolved">
                                                    <button type="submit"
                                                            class="rounded bg-green-500 px-3 py-1 text-white">Resolve</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="p-3 text-center text-gray-500">No pending reports</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Resolved Reports -->
                        <div id="resolved-reports"
                             class="tab-content hidden">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-gray-100 text-left">
                                        <th class="border p-2">Comment</th>
                                        <th class="border p-2">Author</th>
                                        <th class="border p-2">Reported By</th>
                                        <th class="border p-2">Reason</th>
                                        <th class="border p-2">Reviewed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reports->where('status', 'resolved') as $report)
                                        <tr id="report-{{ $report->id }}"
                                            class="border-b">
                                            <td class="border p-2">{{ $report->comment->content }}</td>
                                            <td class="border p-2">{{ $report->comment->user->name }}</td>
                                            <td class="border p-2">{{ $report->user->name }}</td>
                                            <td class="border p-2">
                                                <strong>{{ ucfirst($report->reason) }}</strong><br>
                                                <small>{{ $report->additional_info }}</small>
                                            </td>
                                            <td class="border p-2">
                                                {{ $report->reviewer?->name ?? '—' }}<br>
                                                <small>{{ $report->reviewed_at?->format('M d, Y H:i') }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="p-3 text-center text-gray-500">No resolved reports</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Dismissed Reports -->
                        <div id="dismissed-reports"
                             class="tab-content hidden">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-gray-100 text-left">
                                        <th class="border p-2">Comment</th>
                                        <th class="border p-2">Author</th>
                                        <th class="border p-2">Reported By</th>
                                        <th class="border p-2">Reason</th>
                                        <th class="border p-2">Reviewed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reports->where('status', 'dismissed') as $report)
                                        <tr id="report-{{ $report->id }}"
                                            class="border-b">
                                            <td class="border p-2">{{ $report->comment->content }}</td>
                                            <td class="border p-2">{{ $report->comment->user->name }}</td>
                                            <td class="border p-2">{{ $report->user->name }}</td>
                                            <td class="border p-2">
                                                <strong>{{ ucfirst($report->reason) }}</strong><br>
                                                <small>{{ $report->additional_info }}</small>
                                            </td>
                                            <td class="border p-2">
                                                {{ $report->reviewer?->name ?? '—' }}<br>
                                                <small>{{ $report->reviewed_at?->format('M d, Y H:i') }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="p-3 text-center text-gray-500">No dismissed reports</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs + AJAX -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Tabs
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove(
                            'border-b-2'));
                        btn.classList.add('border-b-2');

                        document.querySelectorAll('.tab-content').forEach(c => c.classList.add(
                            'hidden'));
                        document.getElementById(btn.dataset.target).classList.remove('hidden');
                    });
                });

                // AJAX forms
                document.querySelectorAll('form[data-ajax]').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        let formData = new FormData(this);

                        fetch(this.action, {
                                method: this.method,
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    let row = document.getElementById(`report-${data.report.id}`);
                                    if (row) row.remove();

                                    let targetTable = document.querySelector(
                                        `#${data.report.status}-reports tbody`);
                                    if (targetTable) {
                                        let tr = document.createElement('tr');
                                        tr.innerHTML = `
                            <td class="border p-2">${data.report.comment.content}</td>
                            <td class="border p-2">${data.report.comment.user.name}</td>
                            <td class="border p-2">${data.report.user.name}</td>
                            <td class="border p-2"><strong>${data.report.reason}</strong><br><small>${data.report.additional_info ?? ''}</small></td>
                            <td class="border p-2">${data.report.reviewer ? data.report.reviewer.name : '—'}</td>
                        `;
                                        targetTable.appendChild(tr);
                                    }
                                }
                            });
                    });
                });
            });
        </script>

    </body>

</html>
