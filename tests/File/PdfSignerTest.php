<?php

namespace Tests\File;

use Bezpapirove\BezpapirovePhpLib\File\Handler;
use Bezpapirove\BezpapirovePhpLib\Pdf\PdfSignCertificateAdder;
use PHPUnit\Framework\TestCase;
use Bezpapirove\BezpapirovePhpLib\Helpers\FolderStructure;
use Ramsey\Uuid\Uuid;

final class HandlerTest extends TestCase
{
	private string $path;
	private $f = null;

	private string $pdfFile = '/path/to/your/test.pdf';
	private string $signatureImage = '/path/to/your/testSignature.png';
	private string $outputFile = '/path/to/your/testOutput.pdf';
	private string $privateKeyPath = '/path/to/your/testPrivateKey.pem';
	private string $certificate = '/path/to/your/testCertificate.pem';
	private string $password = 'test_password';

	protected function setUp(): void
	{
		$this->path = sys_get_temp_dir();
	}

	protected function tearDown(): void
	{
		if (false === empty($this->f) && is_file($this->f)) {
			unlink($this->f);
		}
	}

	public function testSignPdf()
	{
		$biz_rule = new PdfSignCertificateAdder(
			$this->pdfFile,
			$this->signatureImage,
			$this->outputFile,
			$this->privateKeyPath
		);


		$this->assertTrue(true);
	}

}
