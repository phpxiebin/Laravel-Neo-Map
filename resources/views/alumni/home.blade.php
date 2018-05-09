@extends('alumni.layouts.app')

{{-- CSS --}}
@section('styles')
    @parent
@endsection

{{-- JS --}}
@section('script')
    @parent
@endsection

@section('content')
    <div class="wrapper" style="padding-top:20px">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox-content m-b-sm border-bottom">
                    <div class="p-xs">
                        <div class="pull-left m-r-md">
                            <i class="fa fa-globe text-navy mid-icon"></i>
                        </div>
                        <h2>欢迎使用 国际公益学院校友平台</h2>
                        <span>使命愿景: 慈善引领社会文明</span>
                    </div>
                    <div class="edit_con_original edit-con-original">
                        <p align="center">
                            <img style="padding-bottom:1em;padding-top:1em;padding-left:1em;padding-right:1em;" alt="" src="http://www.cgpi.org.cn/Upload/image_thum/20160917/20160917152819_6872.jpg" width="824" height="467">
                        </p>
                        {{--<p>--}}
                            {{--<span><strong>国际公益学院</strong>由比尔·盖茨、瑞·达理欧、牛根生、何巧女、叶庆均等五位中美慈善家联合倡议成立，并获得比尔及梅琳达·盖茨基金会、北京达理公益基金会、老牛基金会、北京巧女公益基金会、浙江敦和慈善基金会的共同捐资。学院的举办机构为市亚太国际公益教育基金会。</span>--}}
                        {{--</p>--}}
                        {{--<p>--}}
                            {{--<span>学院旨在建设培养榜样型慈善家和高级公益慈善管理人才的教育系统，构建支持中国与世界公益慈善领域高度发展的知识体系；打造引领全球慈善发展和推动形成新型慈善知识体系的专业智库；通过提升公益慈善事业的创新性、专业化和公众参与，为推进中国和世界慈善事业的发展做出贡献。</span>--}}
                        {{--</p>--}}
                        {{--<p>--}}
                            {{--<span>招商银行原行长马蔚华为国际公益学院董事会主席，北京师范大学教授王振耀为国际公益学院院长。</span>--}}
                        {{--</p>--}}
                        {{--<p>--}}
                            {{--<span>学院运用国际化、实践型、创新性的公益慈善知识生产方式，实行“学术指导+ 实践引领+ 访学研修”三位一体的公益教学新模式，打造“公益慈善组织管理”--}}
                                {{--“公益金融与社会创新”“家族慈善传承”“慈善筹款”等专业教学研究体系。以国际知名大学和具有广泛影响力的基金会和公益慈善组织为研修实践伙伴，通过“全--}}
                                {{--球善财领袖计划（GPL）”和“国际慈善管理（EMP）”两个核心项目，哈佛大学慈善管理高级领导人项目（ＥＬＰ）、中欧公益领导力伙伴项目等国际奖学金计划--}}
                                {{--，以及形式多样的专题认证课程，建设以培养慈善家、慈善组织高级管理人才为主，兼顾职业认证教育和大众公益教育的应用型公益慈善教育培训体系。</span>--}}
                        {{--</p>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection