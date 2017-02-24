<?php

Route::get('visit_line_calendar/pagination', ['uses' => 'VisitLineCalendarController@pagination']);
Route::get('visit_line_calendar/index', ['uses' => 'VisitLineCalendarController@index']);
Route::get('visit_line_calendar/makeVisitLineCalendar', ['uses' => 'VisitLineCalendarController@makeVisitLineCalendar']);
Route::resource('visit_line_calendar', 'VisitLineCalendarController');