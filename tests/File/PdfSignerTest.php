<?php

namespace Tests\File;


use Bezpapirove\BezpapirovePhpLib\Pdf\PdfSignCertificateAdder;
use PHPUnit\Framework\TestCase;

final class PdfSignerTest extends TestCase
{

	private PdfSignCertificateAdder $pdfSigner;
	const SIGNED_PDF = 'path/to/your/signed_output.pdf';
	const CERT_PATH = 'path/to/your/certificate.pem';
	const HASH_DATA = 'your data';

	protected function setUp(): void
	{
		parent::setUp();
		$this->pdfSigner = new PdfSignCertificateAdder(
			'path/to/your/input.pdf',
			'path/to/your/private-key.pem',
			'path/to/your/certificate.pem',
			'your_certificate_password'
		);
	}

	public function testPdfSignCertificateAdder(): void
	{
		$inputPdfPath = 'path/to/your/input.pdf';
		$privateKeyPath = 'path/to/your/private-key.pem';
		$certificatePath = 'path/to/your/certificate.pem';
		$password = 'your_certificate_password';
		$signedPdfPath = 'path/to/your/signed_output.pdf';
		$hashData = 'your data';

		// Instantiate the PdfSignCertificateAdder
		$pdfSigner = new \Bezpapirove\BezpapirovePhpLib\Pdf\PdfSignCertificateAdder(
			$inputPdfPath,
			$privateKeyPath,
			$certificatePath,
			$password
		);

		// Add certificate to signed PDF
		$pdfSigner->addCertificateToSignedPdf($signedPdfPath, $certificatePath, hash('sha256', $hashData));

		// Assert the signed pdf file exists
		$this->assertFileExists($signedPdfPath);

		// Assert the signed pdf is not empty
		$content = file_get_contents($signedPdfPath);
		$this->assertNotEmpty($content);
	}
//	public function testAddCertificateToSignedPdf(): void
//	{
//		$hash = hash('sha256', self::HASH_DATA);
//		try {
//			$this->pdfSigner->addCertificateToSignedPdf(self::SIGNED_PDF, self::CERT_PATH, $hash);
//			$this->assertFileExists(self::SIGNED_PDF);
//			$content = file_get_contents(self::SIGNED_PDF);
//			$this->assertNotEmpty($content);
//			$fileSize = filesize(self::SIGNED_PDF);
//			$this->assertGreaterThan(0, $fileSize, 'Signed PDF file size should be greater than 0');
//			$fileType = mime_content_type(self::SIGNED_PDF);
//			$this->assertEquals('application/pdf', $fileType, 'Signed PDF file should be of type PDF');
//		} catch (\Exception $e) {
//			$this->fail('Unexpected exception thrown: '.$e->getMessage());
//		}
//	}

	public function tearDown(): void
	{
		if (file_exists(self::SIGNED_PDF)) {
			unlink(self::SIGNED_PDF);
		}
		parent::tearDown();
	}


}
