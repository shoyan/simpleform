<?php
require_once('simpletest/autorun.php');
require_once dirname(__FILE__) . '/../simpleform/form.php';

/**
 * form���饹�ϷѾ����ƻ��Ѥ���
 */
class SampleForm extends Form
{
    function SampleForm()
    {
        // validate�˥Х�ǡ������μ������Ͽ���Ƥ����С������å����Ƥ����
        $this->validate = array(
            'name' => array('notEmpty'),
            'mailAddress' => array('notEmpty', 'mail'),
            'mailAddressConfirm' => array('notEmpty', 'mail', 'comparedMail'),
        );

        // �����ѡ����饹�Υ��󥹥ȥ饿��ƤӽФ�
        parent::Form();
    }
}

/**
 * ���Υ��饹��¸�ߤ��ʤ��Х�ǡ�������������Ƥ���Τǥ��顼��ȯ�����롣
 */
class ErrorSampleForm extends Form
{
    function ErrorSampleForm()
    {
        // ¸�ߤ��ʤ��Х�ǡ�����������������ϥ��顼��ȯ������
        $this->validate = array(
            'name' => array('hogehoge'),
        );

        // �����ѡ����饹�Υ��󥹥ȥ饿��ƤӽФ�
        parent::Form();
    }
}


class TestOfForm extends UnitTestCase {
    function testNew()
    {
        $sampleForm = new SampleForm(); 
        
        // validation���֥������Ȥ��ݻ����Ƥ��ʤ��Ȥ����ʤ�
        $this->assertIsA($sampleForm->validation, Validation); 
    }

    /**
     * set�᥽�åɤΥƥ���
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
     * validate�᥽�åɤΥƥ���
     */
    function testValidate()
    {
       $data['name'] = '';
       $data['mailAddress'] = '';
       $data['mailAddressConfirm'] = '';
       $sampleForm = new SampleForm(); 
       
       // set��¹Ԥ��Ƥ��ʤ����ϡ�false���֤�
       $this->assertFalse($sampleForm->validate()); 

       // �Х�ǡ�����󥨥顼���Ǥ�
       $sampleForm->set($data);
       $this->assertFalse($sampleForm->validate());

       // �ǡ��������Ϥ���Ƥ����OK 
       $data['name'] = 'muumuu';
       $data['mailAddress'] = 's-yamasaki@paperboy.co.jp';
       $data['mailAddressConfirm'] = 's-yamasaki@paperboy.co.jp';
 
       $sampleForm = new SampleForm(); 
       $sampleForm->set($data);
       $this->assertTrue($sampleForm->validate());

       // ¸�ߤ��ʤ��Х�ǡ�������������Ƥ������ϡ����顼��ȯ������
       $errorSampleForm = new ErrorSampleForm();
       $errorSampleForm->set($data);

       $this->expectError();
       $errorSampleForm->validate();
    }
}
?>
