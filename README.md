BaiPHP - 化简PHP
================

__化繁为简  四两拨千__

__Tobe Simple & Smart__

声明
----

  BaiPHP - 化简PHP，依据"面向目标"的设计思想，基于"服务-流程-工场"的设计模式，以简洁灵活为方向，由白晓阳设计和开发的一套PHP应用框架。

  BaiPHP - 化简PHP，核心是完全基于配置并即时可控的流程走向和流程覆盖，它采用了简洁优雅的实现方式，不但显著提升框架的易学性和易用性，而且最大限度地释放出应用的灵活性和扩展性，从而尽可能地降低程序的开发和维护成本。

  BaiPHP - 化简PHP，完全开放源代码，任何人都可以复制、修改和使用，但未经授权，不得用于商业目的。任何人都可对该项目提供捐助，捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。

  BaiPHP - 化简PHP，由白晓阳持有版权，并保留一切权利。

面向目标
---------

目标是期望的成果，也是行为的导向。

面向目标，把目光从程序的功能和实现转移到流程的走向和覆盖上来，而对功能的实现进行委托和交付，以从大量底层的逻辑中解脱出来，专注于流程的设置和控制，从而有效地降低系统的耦合性和复杂度，极大的增加系统的灵活性和扩展性。

__面向目标具有以下显著特征：__

-  对外负责：功能委托内部去实现，内部通过实现功能而对外部负责，而且功能的实现不依赖外部。
-  内向影响：外部通过所委托的目标对内部施加影响，内部则根据目标的变化自动适应外部需求。
-  类人性化：内外之间通过目标发生交互，而不必了解对方自身，更接近人的行为习惯和思维方式。

服务-流程-工场
--------------

服务-流程-工场模式以面向目标为基础，将程序划分为服务、流程和工场三个部分。

-  工场：用于实现程序的基本功能，用于接受目标委托并交付处理结果，是程序的基础。
-  流程：用于组织工场和其他流程，用于对目标进行分析和分解并进行委托，是程序的核心。
-  服务：用于接收和组织内外部信息，用于组织目标并进行委托及反馈结果，是程序的界面。

流程处理
--------

用户的每一次请求，都会触发一个目标，用于存储用户请求的事件与数据，流程依据不同的目标名，匹配相应的子流程、工场和服务，从而完成输入验证、数据访问和页面输出等响应。

1.  用户发出请求
2.  根据用户请求组织目标
3.  目标将自身向内委托给流程
4.  流程对目标进行分析和分解，并委托给相应的工场、子流程或服务进行处理
5.  本流程处理完毕，继续对内向其他流程委托目标
6.  目标处理完毕，逐级对外交付
7.  生成响应
