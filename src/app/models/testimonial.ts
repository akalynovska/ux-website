function append(dst, src) {
    for (let key in src) {
        if (src.hasOwnProperty(key)) {
            dst[key] = src[key];
        }
    }
}


export class ApiTestimonial {
    id: number;
    title: string;
    content: string;
    img: null | string;
    author: string;
    author_position: string;
    appendedto: {
        home?: number,
        showcase?: number
    };

    constructor (testimonial?: Testimonial) {
        if (testimonial) {
            this.appendedto = {};
            if (testimonial.home) {
                this.appendedto.home = 1;
            }
            if (testimonial.showcase) {
                this.appendedto.showcase = 1;
            }

            delete testimonial.home;
            delete testimonial.showcase;
            append(this, testimonial);
        }
    }
}


export class Testimonial extends ApiTestimonial {
    home: boolean;
    showcase: boolean;

    constructor (testimonial?: ApiTestimonial) {
        super(); // should do nothing
        if (testimonial) {
            this.home = 'home' in testimonial.appendedto;
            this.showcase = 'showcase' in testimonial.appendedto;
            append(this, testimonial);
        }
    }
}
