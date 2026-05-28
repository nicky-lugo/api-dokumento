<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;


class Uploads extends BaseController
{
	public function singleFileUpload()
	{
		helper(['form']);
		$fileName = dot_array_search('file.name', $_FILES);

		$data = [
			'FileName' => $fileName,
		];

		$rules = [
			'file' => 'max_size[file, 4096]|uploaded[file]'
		];

		if (!$this->validate($rules)) {
			return $this->fail($this->validator->getErrors());
		} else {
			if ($fileName != '') {
				$file = $this->request->getFile('file');
				if (!$file->isValid())
					return $this->fail($file->getErrorString());

				$file->move('./uploads', $fileName);
				$data['FilePath'] = 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $fileName;

				$response['status'] = 'SUCCESS';
				$response['msg'] = "File successfully uploaded.";
				$response['result']  = $data;
				return $this->respondCreated($response);
			}
		}
	}
}

