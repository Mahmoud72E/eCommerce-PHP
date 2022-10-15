<?php 
    
    function lang($phrase)
    {
        static $lang = array(
            // Admin Page
            'Admin Login'       => 'التسجيل لمشرفين',
            'Login'             => 'تسجيل',
            // Dashboard Page
            'Home'              => 'الرئسية',
            'Categories'        => 'الانواع',
            'Items'             => 'الملفات',
            'Members'           => 'الاعضاء',
            'Statistics'        => 'الاحصائيات',
            'Logs'              => 'السجل',
            'Edit Profile'      => 'تعديل بيانتك',
            'Setting'           => 'الاعدادت',
            'Logout'            => 'تسجيل الخروج',
            '' => '',
            '' => '',
            '' => ''
        );

        return $lang[$phrase];
    }

    