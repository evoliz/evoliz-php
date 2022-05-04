<?php

namespace Evoliz\Client\Mapper\Sales;

use Evoliz\Client\Response\Sales\InvoiceResponse;

abstract class InvoiceMapper
{
    public static function mapIntoRequestBody(InvoiceResponse $invoice): array
    {
        $requestBody = [];

        $requestBody['clientid'] = $invoice->client->clientid;
        $requestBody['analyticid'] = $invoice->analytic->id;
        $requestBody['global_rebate'] = $invoice->total->rebate->amount_vat_exclude;
        $requestBody['external_document_number'] = $invoice->external_document_number;
        $requestBody['documentdate'] = $invoice->documentdate;
        $requestBody['contactid'] = $invoice->contactid;
        $requestBody['object'] = $invoice->object;

        $requestBody['term'] = $invoice->term ? self::mapTerm($invoice->term) : null;

        if (isset($invoice->comment) && $invoice->comment !== "") {
            $requestBody['comment'] = $invoice->comment;
        }

        $requestBody['execdate'] = $invoice->execdate;

        $requestBody['retention'] = $invoice->retention ? self::mapRetention($invoice->retention) : null;

        $requestBody['include_sale_general_conditions'] = $invoice->include_sale_general_conditions;

        if (isset($invoice->items)) {
            foreach ($invoice->items as $item) {
                $requestBody['items'][] = self::mapItem($item);
            }
        }

        return array_filter($requestBody, function ($value) {
            return isset($value);
        });
    }

    private static function mapTerm(\stdClass $term): array
    {
        $termBody = [];

        $termBody['paytermid'] = $term->payterm->paytermid;
        $termBody['penalty'] = $term->penalty;
        $termBody['no_penalty'] = $term->nopenalty;
        $termBody['recovery_indemnity'] = $term->recovery_indemnity;
        $termBody['discount_term'] = $term->discount_term;
        $termBody['no_discount_term'] = $term->no_discount_term;
        $termBody['duedate'] = $term->duedate;
        $termBody['paydelay'] = $term->paydelay;
        $termBody['endmonth'] = $term->endmonth;
        $termBody['payday'] = $term->payday;
        $termBody['paytypeid'] = $term->paytype->paytypeid;

        return array_filter($termBody, function ($value) {
            return isset($value);
        });
    }

    private static function mapRetention(\stdClass $retention): array
    {
        $retentionBody = [];

        $retentionBody['percent'] = $retention->percent;
        $retentionBody['date'] = $retention->date;

        return array_filter($retentionBody, function ($value) {
            return isset($value);
        });
    }

    private static function mapItem(\stdClass $item): array
    {
        $itemBody = [];

        $itemBody['articleid'] = $item->articleid;

        $itemBody['reference'] = $item->reference;
        $itemBody['designation'] = $item->designation;
        $itemBody['quantity'] = $item->quantity;
        $itemBody['unit'] = $item->unit;
        $itemBody['unit_price_vat_exclude'] = $item->unit_price_vat_exclude;
        $itemBody['vat_rate'] = $item->vat_rate;
        $itemBody['rebate'] = $item->rebate;
        $itemBody['sale_classificationid'] = $item->sale_classificationid;

        return array_filter($itemBody, function ($value) {
            return isset($value);
        });
    }
}
