<?php

namespace Bezpapirove\BezpapirovePhpLib\Pdf;




//composer require tecnickcom/tcpdf   manipulacia s pdf
// composer require setasign/fpdi


use setasign\Fpdi\Fpdi;

//class PdfSigner {
//	private $pdf;
//	private $signatureImage;
//	private $outputFile;
//
//	public function __construct($pdfFile, $signatureImage, $outputFile) {
//		$this->pdf = new Fpdi();
//		$this->signatureImage = $signatureImage;
//		$this->outputFile = $outputFile;
//		$this->pdf->setSourceFile($pdfFile);
//	}
//
//	public function sign() {
//		// Import all pages
//		$pageCount = $this->pdf->setSourceFile($this->pdfFile);
//		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
//			$tplId = $this->pdf->importPage($pageNo);
//			$this->pdf->addPage();
//			$this->pdf->useTemplate($tplId);
//
//			if ($pageNo == $pageCount) {
//				// Add signature on the last page
//				$this->addSignature();
//			}
//		}
//
//		$this->pdf->Output($this->outputFile, 'F');
//	}
//
//	private function addSignature() {
//		// Position of the signature
//		$x = 150;
//		$y = 250;
//		$width = 50;
//
//		$this->pdf->Image($this->signatureImage, $x, $y, $width);
//	}
//}
//
//// Usage
//$pdfFile = 'path/to/your/input.pdf';
//$signatureImage = 'path/to/your/signature.png';
//$outputFile = 'path/to/your/signed_output.pdf';
//
//$signer = new PdfSigner($pdfFile, $signatureImage, $outputFile);
//$signer->sign();

