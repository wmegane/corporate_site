# corporate_site
ダブルメガネ株式会社公式サイト

## 開発

- sourceディレクトリの中のファイルを修正する。
- データファイルはdataディレクトリに切り出している。

### サーバー起動

```
$ bundle exec middleman server

# or

$ dundle exec middleman

```

[http://localhost:4567](http://localhost:4567)

config page

[http://localhost:4567/__middleman](http://localhost:4567/__middleman)

## デプロイ

githubでmasterブランチへマージをフックに、werckerで自動デプロイするように設定しました。
無料のサービスなのでたまにコケたり、止まったりします。
その際は手動デプロイもしくは、werckerのホームページからexcuteボタンを押すとデプロイできます。

