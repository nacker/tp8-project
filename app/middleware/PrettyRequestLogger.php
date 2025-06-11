<?php
declare (strict_types = 1);

namespace app\middleware;

use Psr\Http\Message\ResponseInterface;
use think\Request;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

class PrettyRequestLogger
{
    public function handle(Request $request, \Closure $next): \think\Response
    {
        $output = new ConsoleOutput();

        // 定义颜色样式
        $styles = [
            'title' => new OutputFormatterStyle('blue', null, ['bold']),
            'key' => new OutputFormatterStyle('green'),
            'value' => new OutputFormatterStyle('yellow'),
            'body' => new OutputFormatterStyle('magenta'),
        ];

        foreach ($styles as $name => $style) {
            $output->getFormatter()->setStyle($name, $style);
        }

        // 打印请求信息标题
        $output->writeln('<title>==================== Request Information ====================</title>');

        // 打印请求方法和 URL
        $output->writeln(sprintf('<key>Method:</key> <value>%s</value>', $request->method()));
        $output->writeln(sprintf('<key>URL:</key> <value>%s</value>', $request->url()));

        // 打印请求头
        $output->writeln('<title>Headers:</title>');
        $headers = $request->header();
        foreach ($headers as $key => $value) {
            $output->writeln(sprintf('  <key>%s:</key> <value>%s</value>', $key, implode(', ', (array)$value)));
        }

        // 打印请求体
        $body = $request->getContent();
        if ($body) {
            $output->writeln('<title>Body:</title>');
            $output->writeln(sprintf('  <body>%s</body>', $body));
        }

        $output->writeln('<title>==============================================================</title>');

        // 继续处理请求
        return $next($request);
    }
}
