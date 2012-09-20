<?php
require_once('simpletest/autorun.php');
require_once dirname(__FILE__) . '/../simpleform/form.php';

/**
 * formクラスは継承して使用する
 */
class SampleForm extends Form
{
    function SampleForm()
    {
        // validateにバリデーションの種類を登録しておけば、チェックしてくれる
        $this->validate = array(
            'name' => array('notEmpty'),
            'mailAddress' => array('notEmpty', 'mail'),
            'mailAddressConfirm' => array('notEmpty', 'mail', 'comparedMail'),
        );

        // スーパークラスのコンストラタを呼び出す
        parent::Form();
    }
}

/**
 * このクラスは存在しないバリデーションを定義しているのでエラーが発生する。
 */
class ErrorSampleForm extends Form
{
    function ErrorSampleForm()
    {
        // 存在しないバリデーションを定義した場合はエラーが発生する
        $this->validate = array(
            'name' => array('hogehoge'),
        );

        // スーパークラスのコンストラタを呼び出す
        parent::Form();
    }
}


class TestOfForm extends UnitTestCase {
    function testNew()
    {
        $sampleForm = new SampleForm(); 
        
        // validationオブジェクトを保持していないといけない
        $this->assertIsA($sampleForm->validation, Validation); 
    }

    /**
     * setメソッドのテスト
     */
    function testSet() 
    {
        $data['a'] = '10';
        $data['b'] = null;
        $data['c'] = 'some string';
        $data['d'] = '';
        $data['e'] = array(1, 2, 3);

        $sampleForm = new SampleForm(); 
        $sampleForm->set($data);
        
        $this->assertEqual($sampleForm->data['a'], '10');
        $this->assertNull($sampleForm->data['b']);
        $this->assertEqual($sampleForm->data['c'], 'some string');
        $this->assertEqual($sampleForm->data['d'], '');
        $this->assertEqual($sampleForm->data['e'], array(1, 2, 3));
    }

    /**
     * validateメソッドのテスト
     */
    function testValidate()
    {
       $data['name'] = '';
       $data['mailAddress'] = '';
       $data['mailAddressConfirm'] = '';
       $sampleForm = new SampleForm(); 
       
       // setを実行していない場合は、falseを返す
       $this->assertFalse($sampleForm->validate()); 

       // バリデーションエラーがでる
       $sampleForm->set($data);
       $this->assertFalse($sampleForm->validate());

       // データが入力されていればOK 
       $data['name'] = 'muumuu';
       $data['mailAddress'] = 's-yamasaki@paperboy.co.jp';
       $data['mailAddressConfirm'] = 's-yamasaki@paperboy.co.jp';
 
       $sampleForm = new SampleForm(); 
       $sampleForm->set($data);
       $this->assertTrue($sampleForm->validate());

       // 存在しないバリデーションが定義されていた場合は、エラーが発生する
       $errorSampleForm = new ErrorSampleForm();
       $errorSampleForm->set($data);

       $this->expectError();
       $errorSampleForm->validate();
    }
}
?>
