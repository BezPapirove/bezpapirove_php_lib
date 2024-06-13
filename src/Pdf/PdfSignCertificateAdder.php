<?php

namespace Bezpapirove\BezpapirovePhpLib\Pdf;

use setasign\Fpdi\Tcpdf;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdfSignCertificateAdder
{
	private Fpdi $pdf;
	private string $privateKeyPath;
	private string $certPath;
	private string $password;

	const HASH_ALGO = 'sha256';
	const SIGN_FIELD_NAME = 'signature_field_name';
	const SIGN_ALGO = OPENSSL_ALGO_SHA256;

	/**
	 * PdfSignCertificateAdder constructor.
	 *
	 * @param string $pdfFile
	 * @param string $privateKeyPath
	 * @param string $certPath
	 * @param string $password
	 *
	 * @throws PdfParserException
	 */
	public function __construct(string $pdfFile, string $privateKeyPath, string $certPath, string $password)
	{
		$this->pdf = new Fpdi();
		$this->privateKeyPath = $privateKeyPath;
		$this->certPath = $certPath;
		$this->password = $password;
		$this->pdf->setSourceFile($pdfFile);
	}


	/**
	 * @throws PdfParserException
	 */
	public function addCertificateToSignedPdf(string $signedPdf, string $output): void
	{

		$this->pdf->setSourceFile($signedPdf);

		// Step 5: Add the signature and the certificate
		$this->pdf->setSignature('file://'. $this->certPath,'file://' . $this->privateKeyPath, $this->password);

		// Step 6: Resave the signed PDF with the added certificate
		$this->pdf->Output($output, 'F');
	}

}

//// Example usage:
//$pdfFile = 'path/to/your/input.pdf';
//$signatureImage = 'path/to/your/signature.png';
//$outputFile = 'path/to/your/signed_output.pdf';
//$privateKeyPath = 'path/to/your/private-key.pem';
//$certificate = 'path/to/your/certificate.pem';
//$password = 'your_certificate_password';
//
//	$signer = new PdfSignCertificateAdder(
//		$pdfFile,
//		$signatureImage,
//		$outputFile,
//		$privateKeyPath
//	);
//$hash = hash_file('sha256', $signedPdf);
//$signer->addCertificateToSignedPdf($signedPdf, $certPath, $hash);

