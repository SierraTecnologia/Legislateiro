<?php

// Route::get('home', 'HomeController@index')->name('home');

Route::resource('/parteTypes', 'ParteTypeController')->parameters([
    'parteTypes' => 'id'
]);
// Route::resource('/competitionPlayer', 'CompetitionPlayerController')->parameters([
//     'competitionPlayer' => 'id'
// ]);






// Route::resource('/points', 'PointController')->parameters([
//     'points' => 'id'
// ]);
// Route::resource('/pointTypes', 'PointTypeController')->parameters([
//     'pointTypes' => 'id'
// ]);





// Route::resource('/jogosEvents', 'JogosEventController')->parameters([
//     'jogosEvents' => 'id'
// ]);
// Route::resource('/players', 'PlayerController')->parameters([
//     'players' => 'id'
// ]);
// Route::resource('/questions', 'QuestionController')->parameters([
//     'questions' => 'id'
// ]);
// Route::resource('/questionResponses', 'QuestionResponseController')->parameters([
//     'questionResponses' => 'id'
// ]);
// Route::resource('/scoreSeries', 'ScoreSerieController')->parameters([
//     'scoreSeries' => 'id'
// ]);
// Route::resource('/scoreSeriePointTypes', 'ScoreSeriePointTypeController')->parameters([
//     'scoreSeriePointTypes' => 'id'
// ]);
// Route::resource('/teams', 'TeamController')->parameters([
//     'teams' => 'id'
// ]);
// Route::resource('/transactions', 'TransactionController')->parameters([
//     'transactions' => 'id'
// ]);