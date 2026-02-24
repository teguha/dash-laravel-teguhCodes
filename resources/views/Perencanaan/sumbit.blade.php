@extends('App.Layout.index')

@section('title')
    submit-perencanaan-data
@endsection

@section('content')
    <main class="p-6">

        {{-- breadcrumb --}}
        @include('App.Partials.breadcrumb', [
            'fields' => [
                'icon' => 'fas fa-cog',
                'parent' => 'Menu',
                'child1' => 'Perencanaan',
                'child1_href' => 'admin.perencanaan.index',
                'child2' => 'Submit'
            ]
        ])

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 mb-2">Submit Perencanaan</h1>
                    <p class="text-sm text-gray-500 mt-1">Manage and submit perencanaan in the system</p>
                </div>
            </div>
        </div>

        
        @php
            
            use App\Models\Approval;
            use App\Models\ApprovalSteps;
            use App\Models\ApprovalStepApprover;
            if($data->status == 'reject'){
                
                $approval = Approval::where('reference_id',$data->id)->first();
                $approval_step = ApprovalSteps::where('approval_id',$approval->id)->where('status', 'rejected')->first();
                $approver = ApprovalStepApprover::where('approval_step_id', $approval_step->id)->where('status', 'rejected')->first();
                if(isset($approver)){
                    $note = $approver->note;
                }else{
                    $note = '';
                }
            }
        @endphp

        @if($data->status == 'reject')
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6 bg-red-100">
                <div class="flex items-center justify-start">
                    <div>
                        <h1 class="text-2xl font-bold text-red-800 mb-2">Reject</h1>
                        <p class="text-sm text-red-500 mt-1">Perencanaan rejected with message {{$note}}</p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.perencanaan.submit.store', $id) }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Text Input - Full Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Perencanaan Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="name" id="name" required value="{{ $data->name }}" class="block w-full pl-10 pr-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter full name">
                    </div>
                </div>

                <!-- Email Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Module Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="text" name="module" id="module" value="{{ $data->module_name }}" readonly class="block w-full pl-10 pr-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="example@email.com">
                    </div>
                </div>

                <!-- Phone Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                            <i class="fas fa-calendar text-gray-400"></i>
                        </div>
                        <input type="text" name="date" id="date" value="{{ date('d M Y', strtotime($data->date)) }}" readonly class="block w-full pl-10 pr-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="+62 812-3456-7890">
                    </div>
                </div>

                <!-- Status Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                            <i class="fas fa-calendar text-gray-400"></i>
                        </div>
                        <input type="text" name="status" readonly value="{{ $data->status }}" class="block w-full pl-10 pr-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor Surat <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                            <i class="fas fa-calendar text-gray-400"></i>
                        </div>
                        <input type="text" name="no" readonly value="{{ $data->no }}" class="block w-full pl-10 pr-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="4" class="block w-full px-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none" placeholder="Write a short bio..."></textarea>
                    <p class="mt-2 text-xs text-gray-500">{{$data->description}}</p>
                </div>


                @if($data->status == 'pending')
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Note
                        </label>
                        <textarea name="note" rows="4" class="block w-full px-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none" placeholder="Write a short bio..."></textarea>
                        <p class="mt-2 text-xs text-gray-500">{{$data->note}}</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <a href="{{route('admin.perencanaan.index')}}" 
                    class="inline-flex items-center px-6 py-3 bg-white border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>

                <div>
                    <button type="submit" id="submit_data" name="submit_data"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/30 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $data->status == 'draft' || $data->status == 'reject' ? 'Submit' : 'Approve' }}
                    </button>
    
                    @if($data->status == 'pending')
                        <button type="submit" id="reject_data" name="reject_data"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 rounded-xl text-sm font-semibold text-white hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-lg shadow-red-500/30 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Reject
                        </button>
                    @endif
                </div>

            </div>
        </form>

        {{-- alert --}}
        <div id="alertContainer"></div>
    </main>

    @php 
        $status = $data->status;

    @endphp


@endsection


@push('custom-scripts')

    <script>
        
        // $(document).on('change', '.check-all', function () {
            const status = @json($status);

            console.log(status);
            if(status == 'draft' || status == 'reject'){
               
                $('#name').prop('disabled', false);
                $('#description').prop('disabled', false);
                $('#note').prop('disabled', true);
            }else{
                $('#name').prop('disabled', true);
                $('#description').prop('disabled', true);
                $('#note').prop('disabled', false);
            }   
        // });
    </script>
    
@endpush

