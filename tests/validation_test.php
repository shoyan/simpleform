<?php
require_once('simpletest/autorun.php');
require_once dirname(__FILE__) . '/../simpleform/validation.php';

class TestOfValidation extends UnitTestCase {
    function testNew()
    {
        // �����ʤ���new����ȥ��顼��������
        $this->expectError();
        $validation = new Validation(); 

        // ���������뤳�Ȥ�validation��new�Ǥ���
        // �����ˤϡ�validation�����פ���ꤹ��
        $validation = new Validation(array()); 
        $this->assertIsA($validation, Validation); 

    }

    /**
     * notEmpty�Υƥ���
     */
    function testNotEmpty()
    {
        $validation = new Validation(array()); 

        $this->assertFalse($validation->notEmpty('', 'key'));
        $this->assertFalse($validation->notEmpty(null, 'key'));
        $this->assertFalse($validation->notEmpty(array(), 'key'));
        $this->assertEqual($validation->validationErrors['key'], 'ɬ�ܹ��ܤ����Ϥ���Ƥ��ޤ���');
        $this->assertTrue($validation->notEmpty('some string', 'key'));
        $this->assertTrue($validation->notEmpty('0', 'key'));
        $this->assertTrue($validation->notEmpty(array(1, 2, 3), 'key'));
    }

    /**
     * phone�Υƥ���
     */
    function testPhone(){
        $validation = new Validation(array()); 

        $this->assertFalse($validation->phone('', 'key'));
        $this->assertFalse($validation->phone(null, 'key'));
        $this->assertFalse($validation->phone(array(), 'key'));
        // �����ʳ���NG
        $this->assertFalse($validation->phone('tel number', 'key'));
        // ���Ѥ�NG
        $this->assertFalse($validation->phone('������', 'key'));
        $this->assertEqual($validation->validationErrors['key'], '�����ֹ�˻��ѤǤ��ʤ�ʸ�����ޤޤ�Ƥ��ޤ�');

        $this->assertTrue($validation->phone('0120-111-111', 'key'));
        $this->assertTrue($validation->phone('0120111111', 'key'));
        // �Ȥ���ʸ�����������å����Ƥ��ʤ��Τǡ��ե����ޥåȤ��ְ�äƤ��Ƥ�OK�ˤʤ롣�������ײ���
        $this->assertTrue($validation->phone('0120', 'key'));
    }

    /**
     * PostCode�Υƥ���
     */
    function testPostCode(){
    
        $validation = new Validation(array()); 
        $this->assertFalse($validation->postCode('', 'key'));
        $this->assertFalse($validation->postCode(null, 'key'));
        $this->assertFalse($validation->postCode(array(), 'key'));
        // �����ȥϥ��ե�ʳ���NG
        $this->assertFalse($validation->postCode('͹���ֹ�', 'key'));
        // ���Ѥ�NG
        $this->assertFalse($validation->postCode('����������������', 'key'));
        // �ϥ��ե�ʤ���NG
        $this->assertFalse($validation->postCode('8111111', 'key'));

        $this->assertEqual($validation->validationErrors['key'], '͹���ֹ�η���������������ޤ���');

        $this->assertTrue($validation->postCode('811-1111', 'key'));
    }

    /**
     * mail�Υƥ���
     */
    function testMail(){
        $validation = new Validation(array()); 
        $this->assertFalse($validation->mail('', 'key'));
        $this->assertFalse($validation->mail(null, 'key'));
        $this->assertFalse($validation->mail(array(), 'key'));
        
        // ���ܸ��NG
        $this->assertFalse($validation->mail('�᡼�륢�ɥ쥹', 'key'));

        $this->assertEqual($validation->validationErrors['key'], '�᡼�륢�ɥ쥹�η���������������ޤ���');

        $this->assertTrue($validation->mail('s-yamasaki@paperboy.co.jp', 'key'));
    
    }

    /**
     * ComparedMail�Υƥ���
     */
    function testComparedMail() {
        $validation = new Validation(array()); 

        // ���Ǥ�Ʊ���ʤ�OK
        $validation->data['mailAddress'] = "";
        $validation->data['mailAddressConfirm'] = "";
        $this->assertTrue($validation->comparedMail('', 'key'));

        $validation->data['mailAddress'] = "s-yamasaki@paperboy.co.jp";
        $validation->data['mailAddressConfirm'] = "";
        $this->assertFalse($validation->comparedMail('', 'key'));

        $validation->data['mailAddress'] = "";
        $validation->data['mailAddressConfirm'] = "s-yamasaki@paperboy.co.jp";
        $this->assertFalse($validation->comparedMail('', 'key'));
        
        $this->assertEqual($validation->validationErrors['key'], '��ǧ�ѤΥ᡼�륢�ɥ쥹�Ȱ��פ��ޤ���');

        $validation->data['mailAddress'] = "s-yamasaki@paperboy.co.jp";
        $validation->data['mailAddressConfirm'] = "s-yamasaki@paperboy.co.jp";
        $this->assertTrue($validation->comparedMail('', 'key'));
    }

    /**
     * numeric�Υƥ���
     */
    function testnumeric(){
        $validation = new Validation(array()); 

        $this->assertFalse($validation->numeric('', 'key'));
        $this->assertFalse($validation->numeric(null, 'key'));
        $this->assertFalse($validation->numeric(array(), 'key'));
        $this->assertFalse($validation->numeric('����������', 'key'));
        $this->assertFalse($validation->numeric('��', 'key'));

        $this->assertEqual($validation->validationErrors['key'], '���Ͱʳ������ϤǤ��ޤ���');

        $this->assertTrue($validation->numeric('1', 'key'));
    }

    // �ʲ��Ϥޤ��᥽�åɤ��ʤ�
    function testBetween(){}
    function testMinLength(){}
    function testMaxLength(){}
}
?>
