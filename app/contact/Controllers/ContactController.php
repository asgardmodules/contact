<?php
namespace Asgard\Contact\Controllers;

class ContactController extends \Asgard\Core\Controller {
	/**
	@Route('contact')
	*/
	public function indexAction($request) {
		$this->form = new \Asgard\Form\Form;
		$this->form->name = new \Asgard\Form\Fields\TextField(array('validation'=>'required'));
		$this->form->email = new \Asgard\Form\Fields\TextField(array('validation'=>array('required', 'email')));
		$this->form->message = new \Asgard\Form\Fields\TextField(array('validation'=>'required'));
		$this->form->captcha = new \Asgard\Form\Fields\CaptchaField;

		if($this->form->isSent()) {
			if($this->form->isValid()) {
				$txt = '';
				foreach($this->form as $field) {
					if($field->name == '_csrf_token')
						continue;
					$txt .= $field->label().': '.$field->getValue()."\n";
				}
				$email = \Asgard\Utils\Email::create(Value::val('email'), $this->form->email->getValue(), 'Contact', $txt);
				// if($this->form->photo->getValue()) {
				// 	$name = Tools::array_get($this->form->photo->getValue(), 'name');
				// 	$path = Tools::array_get($this->form->photo->getValue(), 'path');
				// 	$email->addFile($path, $name);
				// }
				$email->send();

				$this->form->reset();
				\Asgard\Core\App::get('flash')->addSuccess('Merci pour votre message.');
			}
			else {
				\Asgard\Core\App::get('flash')->addError(Tools::flateArray($this->form->errors()));
			}
		}
	}
}