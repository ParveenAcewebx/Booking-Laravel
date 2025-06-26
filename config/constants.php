<?php

return [
        'status' => [
            'active' => 1,
            'inactive' => 0,
        ],
    'role_groups' => [
        'users' => [
            'name' => "Users",
            'slug' => 'users',
            'roles' => ['view users', 'create users','edit users','delete users'] 
        ],
        'roles' => [
            'name' => "Roles",
            'slug' => 'roles',
            'roles' => ['view roles', 'create roles','edit roles','delete roles'] 
        ],
         'forms' => [
            'name' => "Forms",
            'slug' => 'forms',
            'roles' => ['view forms', 'create forms','edit forms','delete forms'] 
        ],
         'bookings' => [
            'name' => "Bookings",
            'slug' => 'bookings',
            'roles' => ['view bookings', 'create bookings', 'edit bookings', 'delete bookings'] 
        ],
         'services' => [
            'name' => "Services",
            'slug' => 'services',
            'roles' => ['view services', 'create services', 'edit services', 'delete services'] 
        ],
    ]
];

