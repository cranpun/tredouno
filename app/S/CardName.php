<?php

namespace App\S;

class CardName
{
    const CARD_PREFIX = "cd_";

    public $prefix;
    public $color;
    public $kind;
    public $num;

    public static function colors(): array
    {
        return ["r", "g", "b", "y"];
    }

    public static function colorName($color): string
    {
        switch ($color) {
            case "r":
                return "赤";
            case "g":
                return "緑";
            case "b":
                return "青";
            case "y":
                return "黄";
            case "a":
                return "wild";
            default:
                return "（エラー）";
        }
    }

    public function __construct(string $cardname)
    {
        $params = explode("_", $cardname);
        if (count($params) != 4) {
            throw new \Exception("誤ったカード名です（{$cardname}");
        }

        $this->prefix = $params[0];
        $this->color = $params[1];
        $this->kind = $params[2];
        $this->num = $params[3];
    }


    public static function cardNames(): array
    {
        // 以下、カードの種類。
        // -3 : 山札
        // -2 : 捨て札
        // -1 : 先頭札
        // https://mattel.co.jp/wp-content/uploads/2022/07/uno_minimalista.pdf

        $ret = [];
        $p = self::CARD_PREFIX;

        foreach (self::colors() as $clr) {
            // // 数字、色ごとに。
            // 1から9
            for ($i = 1; $i <= 9; $i++) {
                // 1～9は2枚ずつ
                $ret[] = "{$p}{$clr}_num0{$i}_1";
                $ret[] = "{$p}{$clr}_num0{$i}_2";
            }
            // // 数字0は1枚だけ
            $ret[] = "{$p}{$clr}_num00_1";

            // 文字カードも2枚ずつ
            foreach ([
                "draw2",
                "reverse",
                "skip",
            ] as $chr) {
                $ret[] = "{$p}{$clr}_{$chr}_1";
                $ret[] = "{$p}{$clr}_{$chr}_2";
            }
        }

        // ワイルドカードは色関係なく8枚
        for ($i = 1; $i <= 8; $i++) {
            $ret[] = "{$p}a_wild_{$i}";
        }

        // ワイルドドロー4カードは色関係なく4枚
        for ($i = 1; $i <= 4; $i++) {
            $ret[] = "{$p}a_wild4_{$i}";
        }

        return $ret;
    }

    public static function isNum($cname): bool
    {
        $ret = str_contains($cname, "num");
        return $ret;
    }

    public static function cardNamesOnlyNum(): array
    {
        $cards = self::cardNames();
        $ret = [];
        foreach ($cards as $card) {
            if (self::isNum($card)) {
                $ret[] = $card;
            }
        }
        return $ret;
    }

    public static function canPutCard($cname1, $head, $event, $data): bool
    {
        $obj1 = new CardName($cname1);
        $objH = new CardName($head);

        if (in_array($event, [\App\L\CardEvent::ID_AFTERPULL]) && $data) {
            // AFTERPULLの場合、evetndataに退避したデータがあるかもしれないのでそれを確認
            $oData = json_decode($data);
            if($obj1->color == $oData->eventdata) {
                return true;
            }
        }

        if (in_array($event, [\App\L\CardEvent::ID_WILD, \App\L\CardEvent::ID_WILD4])) {
            // ワイルドカードだったのでheadではなく、dataを確認。MYTODO wild4やdrawは別対応になるかも？
            if ($obj1->color == $data) {
                return true;
            }
        }
        if (in_array($event, [\App\L\CardEvent::ID_DRAW2, \App\L\CardEvent::ID_WILD4])) {
            // 前に出されたカードが攻撃であればどのカードでも出せない
            return false;
        }
        if ($obj1->color == $objH->color) {
            // 同じ色
            return true;
        }
        if ($obj1->kind == $objH->kind) {
            // 同じ種類
            return true;
        }
        if (in_array($obj1->kind, ["wild", "wild4"])) {
            // このカードがワイルド系
            return true;
        }

        // それ以外は提出不可
        return false;
    }

    public static function makePutGroups(array $cardnames): array
    {
        $ret = [];

        foreach ($cardnames as $cname1) {
            $data = [$cname1]; // まず自分自身
            $obj1 = new \App\S\CardName($cname1);
            foreach ($cardnames as $cname2) {
                $obj2 = new \App\S\CardName($cname2);
                // 種類が一緒（かつ自分自身でない）ならグループ
                if (($obj1->kind === $obj2->kind)
                    && ($cname1 !== $cname2)
                ) {
                    $data[] = $cname2;
                }
            }

            if (count($data) > 1) {
                // グループであれば
                sort($data);
                if (!in_array($data, $ret)) {
                    // 同一のグループが存在しない（始めて検出）
                    $ret[] = $data;
                }
            }
        }

        return $ret;
    }
}
