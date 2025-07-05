
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Full Calendar</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Components</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Full Calender</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW OPEN -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Full Calendar Events</h3>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="row" id='wrap'>
                                                <div class="col-xl-2" id='external-events'>
                                                <h4>Draggable Events</h4>

                                                <div id='external-events-list'>
                                                    <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
                                                    <div class='fc-event-main'>My Event 1</div>
                                                    </div>
                                                    <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
                                                    <div class='fc-event-main'>My Event 2</div>
                                                    </div>
                                                    <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
                                                    <div class='fc-event-main'>My Event 3</div>
                                                    </div>
                                                    <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
                                                    <div class='fc-event-main'>My Event 4</div>
                                                    </div>
                                                    <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
                                                    <div class='fc-event-main'>My Event 5</div>
                                                    </div>
                                                </div>

                                                <p>
                                                    <input type='checkbox' id='drop-remove' >
                                                    <label for='drop-remove' class="text-dark">remove after drop</label>
                                                </p>

                                                </div>

                                                <div class="col-xl-10" id='calendar-wrap'>
                                                <div id='calendar'></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">List Calendar</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id='calendar1'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW CLOSED -->

@endsection

@section('scripts')

		<!-- FULL CALENDAR JS -->
		<script src='{{asset('build/assets/plugins/fullcalendar/fullcalendar.min.js')}}'></script>
		@vite('resources/assets/js/fullcalendar.js')

@endsection
