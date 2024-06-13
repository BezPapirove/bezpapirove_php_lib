<?php

namespace Tests\File;


use Bezpapirove\BezpapirovePhpLib\Pdf\PdfSignCertificateAdder;
use PHPUnit\Framework\TestCase;

final class PdfSignerTest extends TestCase
{

	protected function setUp(): void
	{
		parent::setUp();
	}

	public function testPdfSignCertificateAdder(): void
	{
		$inputPdfPath = __DIR__ . '/sample.pdf';
		$outputPdfPath = __DIR__ . '/signed_sample.pdf';
		$certificate =  __DIR__ . '/../../src/Pdf/tcpdf.crt';
		$password = 'qawsed';

		// Assert if files exist and is readable
		$this->assertFileExists($inputPdfPath);
		$this->assertFileExists($certificate);
		$this->assertFileIsReadable($inputPdfPath);
		$this->assertFileIsReadable($certificate);

		// Instantiate the PdfSignCertificateAdder
		$pdfSigner = new PdfSignCertificateAdder($inputPdfPath);

		$info = array(
			'Name' => 'BezPapirove',
			'Location' => 'Brno',
			'Reason' => 'Testing',
			'ContactInfo' => 'http://www.bezpapirove.cz',
		);

		$result = $pdfSigner->addCertificateToSignedPdf($outputPdfPath, $certificate, $password, $info);
		if ($result === false) {
			$this->assertTrue($pdfSigner->isError());
			$this->assertFalse(empty($pdfSigner->getError()));
		} else {
			$this->assertFileExists($outputPdfPath);
			$this->assertEmpty($pdfSigner->getError());
			$this->assertFalse($pdfSigner->isError());
		}

		$content = file_get_contents($outputPdfPath);
		$this->assertNotEmpty($content);
	}

	public function tearDown(): void
	{
		parent::tearDown();
	}


}
