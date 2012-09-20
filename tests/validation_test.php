<?php
require_once('simpletest/autorun.php');
require_once dirname(__FILE__) . '/../simpleform/validation.php';

class TestOfValidation extends UnitTestCase {
    function testNew()
    {
        // 引数なしでnewするとエラーが起きる
        $this->expectError();
        $validation = new Validation(); 

        // 引数があることでvalidationがnewできる
        // 引数には、validationタイプを指定する
        $validation = new Validation(array()); 
        $this->assertIsA($validation, Validation); 

    }

    /**
     * notEmptyのテスト
     */
    function testNotEmpty()
    {
        $validation = new Validation(array()); 

        $this->assertFalse($validation->notEmpty('', 'key'));
        $this->assertFalse($validation->notEmpty(null, 'key'));
        $this->assertFalse($validation->notEmpty(array(), 'key'));
        $this->assertEqual($validation->validationErrors['key'], '必須項目が入力されていません');
        $this->assertTrue($validation->notEmpty('some string', 'key'));
        $this->assertTrue($validation->notEmpty('0', 'key'));
        $this->assertTrue($validation->notEmpty(array(1, 2, 3), 'key'));
    }

    /**
     * phoneのテスト
     */
    function testPhone(){
        $validation = new Validation(array()); 

        $this->assertFalse($validation->phone('', 'key'));
        $this->assertFalse($validation->phone(null, 'key'));
        $this->assertFalse($validation->phone(array(), 'key'));
        // 数字以外はNG
        $this->assertFalse($validation->phone('tel number', 'key'));
        // 全角はNG
        $this->assertFalse($validation->phone('０１２', 'key'));
        $this->assertEqual($validation->validationErrors['key'], '電話番号に使用できない文字が含まれています');

        $this->assertTrue($validation->phone('0120-111-111', 'key'));
        $this->assertTrue($validation->phone('0120111111', 'key'));
        // 使える文字しかチェックしていないので、フォーマットが間違っていてもOKになる。ここは要改善
        $this->assertTrue($validation->phone('0120', 'key'));
    }

    /**
     * PostCodeのテスト
     */
    function testPostCode(){
    
        $validation = new Validation(array()); 
        $this->assertFalse($validation->postCode('', 'key'));
        $this->assertFalse($validation->postCode(null, 'key'));
        $this->assertFalse($validation->postCode(array(), 'key'));
        // 数字とハイフン以外はNG
        $this->assertFalse($validation->postCode('郵便番号', 'key'));
        // 全角はNG
        $this->assertFalse($validation->postCode('１１１ー１１１１', 'key'));
        // ハイフンなしはNG
        $this->assertFalse($validation->postCode('8111111', 'key'));

        $this->assertEqual($validation->validationErrors['key'], '郵便番号の形式が正しくありません');

        $this->assertTrue($validation->postCode('811-1111', 'key'));
    }

    /**
     * mailのテスト
     */
    function testMail(){
        $validation = new Validation(array()); 
        $this->assertFalse($validation->mail('', 'key'));
        $this->assertFalse($validation->mail(null, 'key'));
        $this->assertFalse($validation->mail(array(), 'key'));
        
        // 日本語はNG
        $this->assertFalse($validation->mail('メールアドレス', 'key'));

        $this->assertEqual($validation->validationErrors['key'], 'メールアドレスの形式が正しくありません');

        $this->assertTrue($validation->mail('s-yamasaki@paperboy.co.jp', 'key'));
    
    }

    /**
     * ComparedMailのテスト
     */
    function testComparedMail() {
        $validation = new Validation(array()); 

        // 空でも同じならOK
        $validation->data['mailAddress'] = "";
        $validation->data['mailAddressConfirm'] = "";
        $this->assertTrue($validation->comparedMail('', 'key'));

        $validation->data['mailAddress'] = "s-yamasaki@paperboy.co.jp";
        $validation->data['mailAddressConfirm'] = "";
        $this->assertFalse($validation->comparedMail('', 'key'));

        $validation->data['mailAddress'] = "";
        $validation->data['mailAddressConfirm'] = "s-yamasaki@paperboy.co.jp";
        $this->assertFalse($validation->comparedMail('', 'key'));
        
        $this->assertEqual($validation->validationErrors['key'], '確認用のメールアドレスと一致しません');

        $validation->data['mailAddress'] = "s-yamasaki@paperboy.co.jp";
        $validation->data['mailAddressConfirm'] = "s-yamasaki@paperboy.co.jp";
        $this->assertTrue($validation->comparedMail('', 'key'));
    }

    /**
     * numericのテスト
     */
    function testnumeric(){
        $validation = new Validation(array()); 

        $this->assertFalse($validation->numeric('', 'key'));
        $this->assertFalse($validation->numeric(null, 'key'));
        $this->assertFalse($validation->numeric(array(), 'key'));
        $this->assertFalse($validation->numeric('あいうえお', 'key'));
        $this->assertFalse($validation->numeric('１', 'key'));

        $this->assertEqual($validation->validationErrors['key'], '数値以外は入力できません');

        $this->assertTrue($validation->numeric('1', 'key'));
    }

    // 以下はまだメソッドがない
    function testBetween(){}
    function testMinLength(){}
    function testMaxLength(){}
}
?>
