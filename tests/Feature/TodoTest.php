<?php

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});


test('createTodo' ,function() {
    
});