// core version + selected modules:
import SwiperCore from 'swiper';
import { Keyboard, Mousewheel, Navigation, Pagination, Zoom, Controller, A11y, Autoplay, EffectFade, Thumbs } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/a11y';
import 'swiper/css/autoplay';
import 'swiper/css/controller';
import 'swiper/css/effect-fade';
import 'swiper/css/free-mode';
import 'swiper/css/hash-navigation';
import 'swiper/css/keyboard';
import 'swiper/css/mousewheel';
import 'swiper/css/navigation';
import 'swiper/css/zoom';
import 'swiper/css/thumbs';
// import 'swiper/css/pagination';

SwiperCore.use([Keyboard, Mousewheel, Navigation, Pagination, Zoom, Controller, A11y, Autoplay, EffectFade, Thumbs]);

export default SwiperCore;
