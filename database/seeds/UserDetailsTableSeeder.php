<?php

use Illuminate\Database\Seeder;

class UserDetailsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_details')->delete();
        \ DB::statement('SET IDENTITY_INSERT user_details ON');
        \DB::table('user_details')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 3,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 1,
                'user_level' => 1,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-04-05 03:29:00',
                'updated_at' => '2018-04-05 06:15:44',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 4,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 1,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 06:39:08',
                'updated_at' => '2018-04-05 06:39:08',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 5,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 1,
                'user_level' => 2,
                'can_approve' => 1,
                'description' => 'Manager',
                'created_at' => '2018-04-05 06:43:33',
                'updated_at' => '2018-06-11 05:54:37',
                'deleted_at' => '2018-06-11 05:54:37',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 6,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 2,
                'user_level' => 4,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 06:51:29',
                'updated_at' => '2018-04-05 06:51:29',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 7,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 3,
                'user_level' => 3,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 06:52:44',
                'updated_at' => '2018-04-05 06:52:44',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'user_id' => 8,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 4,
                'user_level' => 2,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 06:53:52',
                'updated_at' => '2018-04-05 06:53:52',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'user_id' => 9,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 5,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 06:54:29',
                'updated_at' => '2018-06-25 03:56:34',
                'deleted_at' => '2018-06-25 03:56:34',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'user_id' => 10,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 3,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 07:04:36',
                'updated_at' => '2018-04-05 07:04:36',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'user_id' => 11,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 1,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 08:42:56',
                'updated_at' => '2018-06-25 04:01:23',
                'deleted_at' => '2018-06-25 04:01:23',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'user_id' => 11,
                'mappingperusahaan_id' => 3,
                'user_jabatan_id' => 6,
                'user_level' => 5,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 08:52:13',
                'updated_at' => '2018-04-05 08:54:48',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'user_id' => 11,
                'mappingperusahaan_id' => 4,
                'user_jabatan_id' => 6,
                'user_level' => 5,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 08:52:23',
                'updated_at' => '2018-04-05 08:54:59',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'user_id' => 12,
                'mappingperusahaan_id' => 2,
                'user_jabatan_id' => 7,
                'user_level' => 6,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 09:04:46',
                'updated_at' => '2018-04-05 09:04:46',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'user_id' => 13,
                'mappingperusahaan_id' => 3,
                'user_jabatan_id' => 7,
                'user_level' => 6,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 09:05:34',
                'updated_at' => '2018-04-05 09:05:34',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'user_id' => 14,
                'mappingperusahaan_id' => 4,
                'user_jabatan_id' => 7,
                'user_level' => 6,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 09:06:03',
                'updated_at' => '2018-04-05 09:06:03',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'user_id' => 15,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 2,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-05 09:15:34',
                'updated_at' => '2018-05-07 04:25:14',
                'deleted_at' => '2018-05-07 04:25:14',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'user_id' => 1,
                'mappingperusahaan_id' => 2,
                'user_jabatan_id' => 2,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-04-06 10:19:15',
                'updated_at' => '2018-07-05 06:47:26',
                'deleted_at' => '2018-07-05 06:47:26',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'user_id' => 1,
                'mappingperusahaan_id' => 5,
                'user_jabatan_id' => 7,
                'user_level' => 7,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-05-04 05:59:58',
                'updated_at' => '2018-07-05 06:47:29',
                'deleted_at' => '2018-07-05 06:47:29',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'user_id' => 16,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 2,
                'user_level' => 2,
                'can_approve' => 0,
                'description' => 'PIC',
                'created_at' => '2018-05-28 07:51:14',
                'updated_at' => '2018-05-28 07:53:03',
                'deleted_at' => '2018-05-28 07:53:03',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'user_id' => 16,
                'mappingperusahaan_id' => 6,
                'user_jabatan_id' => 8,
                'user_level' => 8,
                'can_approve' => 0,
                'description' => 'PIC PROJECT',
                'created_at' => '2018-05-28 07:53:50',
                'updated_at' => '2018-05-28 07:53:50',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'user_id' => 5,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 5,
                'user_level' => 5,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-06-11 06:11:04',
                'updated_at' => '2018-06-11 06:11:04',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'user_id' => 1,
                'mappingperusahaan_id' => 7,
                'user_jabatan_id' => 8,
                'user_level' => 8,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-06-15 04:28:19',
                'updated_at' => '2018-07-05 06:47:31',
                'deleted_at' => '2018-07-05 06:47:31',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'user_id' => 9,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 1,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-06-25 03:56:29',
                'updated_at' => '2018-06-25 03:56:29',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'user_id' => 24,
                'mappingperusahaan_id' => 20,
                'user_jabatan_id' => 1,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-10-05 10:10:16',
                'updated_at' => '2018-10-10 04:28:35',
                'deleted_at' => '2018-10-10 04:28:35',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'user_id' => 24,
                'mappingperusahaan_id' => 21,
                'user_jabatan_id' => 1,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-10-05 10:10:16',
                'updated_at' => '2018-10-10 04:28:35',
                'deleted_at' => '2018-10-10 04:28:35',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'user_id' => 25,
                'mappingperusahaan_id' => 19,
                'user_jabatan_id' => 4,
                'user_level' => 4,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-10-07 12:28:15',
                'updated_at' => '2018-10-07 12:28:15',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 39,
            ),
            25 => 
            array (
                'id' => 26,
                'user_id' => 26,
                'mappingperusahaan_id' => 19,
                'user_jabatan_id' => 5,
                'user_level' => 5,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-10-07 12:29:24',
                'updated_at' => '2018-10-07 12:29:24',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 39,
            ),
            26 => 
            array (
                'id' => 27,
                'user_id' => 28,
                'mappingperusahaan_id' => 19,
                'user_jabatan_id' => 2,
                'user_level' => 2,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-10-07 13:38:34',
                'updated_at' => '2018-10-07 13:38:34',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 39,
            ),
            27 => 
            array (
                'id' => 28,
                'user_id' => 27,
                'mappingperusahaan_id' => 19,
                'user_jabatan_id' => 6,
                'user_level' => 6,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-10-07 13:53:56',
                'updated_at' => '2018-10-07 13:53:56',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 39,
            ),
            28 => 
            array (
                'id' => 29,
                'user_id' => 29,
                'mappingperusahaan_id' => 19,
                'user_jabatan_id' => 7,
                'user_level' => 7,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-10-07 14:05:04',
                'updated_at' => '2018-10-07 14:05:04',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 39,
            ),
            29 => 
            array (
                'id' => 30,
                'user_id' => 30,
                'mappingperusahaan_id' => 19,
                'user_jabatan_id' => 10,
                'user_level' => 10,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-10-08 03:13:43',
                'updated_at' => '2018-10-08 03:13:43',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 39,
            ),
            30 => 
            array (
                'id' => 31,
                'user_id' => 24,
                'mappingperusahaan_id' => 20,
                'user_jabatan_id' => 1,
                'user_level' => 1,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-10-10 04:28:04',
                'updated_at' => '2018-10-10 04:28:35',
                'deleted_at' => '2018-10-10 04:28:35',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 40,
            ),
            31 => 
            array (
                'id' => 32,
                'user_id' => 24,
                'mappingperusahaan_id' => 21,
                'user_jabatan_id' => 1,
                'user_level' => 1,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-10-10 04:28:04',
                'updated_at' => '2018-10-10 04:28:35',
                'deleted_at' => '2018-10-10 04:28:35',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 40,
            ),
            32 => 
            array (
                'id' => 33,
                'user_id' => 24,
                'mappingperusahaan_id' => 20,
                'user_jabatan_id' => 4,
                'user_level' => 4,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-10-10 04:28:14',
                'updated_at' => '2018-10-10 04:29:06',
                'deleted_at' => '2018-10-10 04:29:06',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 40,
            ),
            33 => 
            array (
                'id' => 34,
                'user_id' => 24,
                'mappingperusahaan_id' => 21,
                'user_jabatan_id' => 4,
                'user_level' => 4,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-10-10 04:28:14',
                'updated_at' => '2018-10-10 04:29:07',
                'deleted_at' => '2018-10-10 04:29:07',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 40,
            ),
            34 => 
            array (
                'id' => 35,
                'user_id' => 24,
                'mappingperusahaan_id' => 20,
                'user_jabatan_id' => 10,
                'user_level' => 10,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-10-10 04:28:27',
                'updated_at' => '2018-10-10 04:29:17',
                'deleted_at' => '2018-10-10 04:29:17',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 40,
            ),
            35 => 
            array (
                'id' => 36,
                'user_id' => 24,
                'mappingperusahaan_id' => 21,
                'user_jabatan_id' => 10,
                'user_level' => 10,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-10-10 04:28:27',
                'updated_at' => '2018-10-10 04:29:17',
                'deleted_at' => '2018-10-10 04:29:17',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 40,
            ),
            36 => 
            array (
                'id' => 37,
                'user_id' => 24,
                'mappingperusahaan_id' => 20,
                'user_jabatan_id' => 1,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-10-10 04:29:32',
                'updated_at' => '2018-10-10 04:29:32',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 40,
            ),
            37 => 
            array (
                'id' => 38,
                'user_id' => 24,
                'mappingperusahaan_id' => 21,
                'user_jabatan_id' => 1,
                'user_level' => 1,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-10-10 04:29:32',
                'updated_at' => '2018-10-10 04:29:32',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 40,
            ),
            38 => 
            array (
                'id' => 39,
                'user_id' => 31,
                'mappingperusahaan_id' => 24,
                'user_jabatan_id' => 10,
                'user_level' => 10,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-10-29 10:36:22',
                'updated_at' => '2018-10-29 10:36:22',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 43,
            ),
            39 => 
            array (
                'id' => 40,
                'user_id' => 32,
                'mappingperusahaan_id' => 2,
                'user_jabatan_id' => 10,
                'user_level' => 10,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-10-30 03:37:10',
                'updated_at' => '2018-10-30 03:37:10',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 44,
            ),
            40 => 
            array (
                'id' => 41,
                'user_id' => 32,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 10,
                'user_level' => 10,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-10-30 03:37:10',
                'updated_at' => '2018-10-30 03:37:10',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 44,
            ),
            41 => 
            array (
                'id' => 42,
                'user_id' => 37,
                'mappingperusahaan_id' => 25,
                'user_jabatan_id' => 10,
                'user_level' => 10,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-11-09 11:18:42',
                'updated_at' => '2018-11-09 11:18:42',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 45,
            ),
            42 => 
            array (
                'id' => 43,
                'user_id' => 37,
                'mappingperusahaan_id' => 25,
                'user_jabatan_id' => 10,
                'user_level' => 10,
                'can_approve' => 0,
                'description' => NULL,
                'created_at' => '2018-11-09 11:18:43',
                'updated_at' => '2018-11-09 11:18:43',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 45,
            ),
            43 => 
            array (
                'id' => 44,
                'user_id' => 25,
                'mappingperusahaan_id' => 2,
                'user_jabatan_id' => 2,
                'user_level' => 2,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 11:23:04',
                'updated_at' => '2018-11-09 11:23:04',
                'deleted_at' => NULL,
                'created_by' => 32,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 46,
            ),
            44 => 
            array (
                'id' => 45,
                'user_id' => 25,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 2,
                'user_level' => 2,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 11:23:04',
                'updated_at' => '2018-11-09 11:23:04',
                'deleted_at' => NULL,
                'created_by' => 32,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 46,
            ),
            45 => 
            array (
                'id' => 46,
                'user_id' => 25,
                'mappingperusahaan_id' => 2,
                'user_jabatan_id' => 4,
                'user_level' => 4,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 11:33:05',
                'updated_at' => '2018-11-09 11:33:05',
                'deleted_at' => NULL,
                'created_by' => 32,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 47,
            ),
            46 => 
            array (
                'id' => 47,
                'user_id' => 25,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 4,
                'user_level' => 4,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 11:33:05',
                'updated_at' => '2018-11-09 11:33:05',
                'deleted_at' => NULL,
                'created_by' => 32,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 47,
            ),
            47 => 
            array (
                'id' => 48,
                'user_id' => 28,
                'mappingperusahaan_id' => 2,
                'user_jabatan_id' => 2,
                'user_level' => 2,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 11:34:08',
                'updated_at' => '2018-11-09 11:34:08',
                'deleted_at' => NULL,
                'created_by' => 32,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 48,
            ),
            48 => 
            array (
                'id' => 49,
                'user_id' => 28,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 2,
                'user_level' => 2,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 11:34:08',
                'updated_at' => '2018-11-09 11:34:08',
                'deleted_at' => NULL,
                'created_by' => 32,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 48,
            ),
            49 => 
            array (
                'id' => 50,
                'user_id' => 38,
                'mappingperusahaan_id' => 2,
                'user_jabatan_id' => 3,
                'user_level' => 3,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 11:45:06',
                'updated_at' => '2018-11-09 11:45:06',
                'deleted_at' => NULL,
                'created_by' => 32,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 49,
            ),
            50 => 
            array (
                'id' => 51,
                'user_id' => 38,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 3,
                'user_level' => 3,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 11:45:06',
                'updated_at' => '2018-11-09 11:45:06',
                'deleted_at' => NULL,
                'created_by' => 32,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 49,
            ),
            51 => 
            array (
                'id' => 52,
                'user_id' => 39,
                'mappingperusahaan_id' => 2,
                'user_jabatan_id' => 5,
                'user_level' => 5,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 15:53:02',
                'updated_at' => '2018-11-09 15:53:02',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 50,
            ),
            52 => 
            array (
                'id' => 53,
                'user_id' => 39,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 5,
                'user_level' => 5,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 15:53:02',
                'updated_at' => '2018-11-09 15:53:02',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 50,
            ),
            53 => 
            array (
                'id' => 54,
                'user_id' => 40,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 6,
                'user_level' => 6,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 15:55:45',
                'updated_at' => '2018-11-09 15:55:45',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 51,
            ),
            54 => 
            array (
                'id' => 55,
                'user_id' => 41,
                'mappingperusahaan_id' => 1,
                'user_jabatan_id' => 7,
                'user_level' => 7,
                'can_approve' => 1,
                'description' => NULL,
                'created_at' => '2018-11-09 15:56:47',
                'updated_at' => '2018-11-09 15:56:47',
                'deleted_at' => NULL,
                'created_by' => 1,
                'updated_by' => NULL,
                'deleted_by' => NULL,
                'inactive_at' => NULL,
                'inactive_by' => NULL,
                'project_pt_id' => 52,
            ),
        ));
        
        
    }
}