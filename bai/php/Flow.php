<?php
/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * @link      http://www.baiphp.net
 * @copyright Copyright 2011 - 2013, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2013/07/01 全面重构代码，弃用公共函数，独立启动引擎，优化配置文件结构，增加
 *            原始虚类、目标工场、样式工场、模板工场、语言工场和记录工场，并优化代码结构。
 * @license
 * <p>化简PHP（BaiPHP）开发框架，是依据"面向目标"的设计思想、基于"服务-流程-工场"的设计模式、以简洁
 * 灵活为方向、由白晓阳设计和开发的一套PHP应用框架。该框架的核心是基于配置并即时可控的流程走向和流程覆
 * 盖，它采用了简洁优雅的实现方式，不但显著提升框架的易学性和易用性，而且最大限度地释放出应用的灵活性和扩
 * 展性，从而尽可能地降低程序的开发和维护成本。</p>
 * <p>化简PHP（BaiPHP）开发框架完全开放源代码，任何人都可以自由地复制、传播、修改和使用该代码，但未经
 * 授权，不得用于商业目的。</p>
 * <p>欢迎对该框架提供任何形式的捐助，捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授
 * 权。</p>
 * <p>化简PHP（BaiPHP）开发框架由白晓阳持有版权，并保留一切权利。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>流程</h3>
 * <p>流程具备下述特点：</p>
 * <ol>
 * <li>流程是精明的组织者，通过有序的组织工场以及与其他流程相复合，进而实现高级的复杂的功能。</li>
 * <li>流程相对易变，入口数据的不同可能导致流程的改变。</li>
 * <li>流程只开放一个常规公开入口。</li>
 * <li>流程具备动态执行自身方法的能力。</li>
 * <li>流程具备动态获取与使用工场和其他流程的能力。</li>
 * <li>流程能够按照预置程序（像一道流水线一样）智能运作。</li>
 * </ol>
 */
abstract class Flow extends Bai
{
	/** 标识：调度流程 */
	const CONTROL = 'Control';
	/** 标识：处理流程 */
	const ACTION  = 'Action';
	/** 标识：页面流程 */
	const PAGE    = 'Page';

	/**
	 * <h4>预处理</h4>
	 * @return boolean
	 */
	protected function prepare()
	{
		return true;
	}
	/**
	 * <h4>自定义事务</h4>
	 * @return boolean
	 */
	protected function engage()
	{
		return true;
	}
}
