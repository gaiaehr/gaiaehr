<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 1/18/13
 * Time: 12:59 AM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)){
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
class Snippets {

    public function getSoapSnippetsByCategory($params){

        $foo = array(
            array(
                'text' => 'Normal Tests',
                'iconCls' => 'task-folder',
                'leaf' => false,
                'expanded' => true,
                'children' => array(
                    array(
                        'text' => 'Start an online fundraiser for out-of-pocket medical expenses today! Covering medical bills is a scary prospect with or without health insurance.',
                        'iconCls' => 'task',
                        'leaf' => true
                    ),
                    array(
                        'text' => 'Bizcommunity.com - Daily Medical news .... News for medical professionals.',
                        'iconCls' => 'task',
                        'leaf' => true
                    ),
                    array(
                        'text' => 'The leading source for trustworthy and timely health and medical',
                        'iconCls' => 'task',
                        'leaf' => true
                    ),
                    array(
                        'text' => 'The medical decision-making (MDM) process involves analysis',
                        'iconCls' => 'task',
                        'leaf' => true
                    )
                )
            ),
            array(
                'text' => 'Questions Test',
                'iconCls' => 'task-folder',
                'leaf' => false,
                'children' => array(
                    array(
                        'text' => 'This tool has the ?? to query other search engines, also provides',
                        'iconCls' => 'task',
                        'leaf' => true
                    ),
                    array(
                        'text' => 'Medical Humanities is a leading international journal that reflects ??',
                        'iconCls' => 'task',
                        'leaf' => true
                    ),
                    array(
                        'text' => '?? Years',
                        'iconCls' => 'task',
                        'leaf' => true
                    ),
                    array(
                        'text' => 'Color ??',
                        'iconCls' => 'task',
                        'leaf' => true
                    )

                )
            )
        );

        return $foo;

    }
}

//$t = new Templates();
//print '<pre>';
//print_r($t->getSoapTemplatesByCategory(''));
