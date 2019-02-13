import { Component, OnInit, Inject } from '@angular/core';
import { TestimonialService } from '../../services/testimonial.service';
//import { DOCUMENT } from '@angular/platform-browser';

import { Testimonial } from '../../models/testimonial'


@Component({
    selector: 'app-dashboard',
    templateUrl: './dashboard.component.html',
    styleUrls: ['./dashboard.component.scss'],
    providers: [TestimonialService]
})

export class DashboardComponent implements OnInit {
    newTestimonial: Testimonial;
    testimonials: Testimonial[];

    constructor(
        //@Inject(DOCUMENT) private document: any,
        private testimonialService: TestimonialService
    ) { }

    ngOnInit() {
        this.newTestimonial = new Testimonial();
        this.testimonialService.get().then(items => this.testimonials = items);
    }

    putImage(item, $event, uploadInstantly) {
        if ($event.target.files && $event.target.files[0]) {
            let reader = new FileReader();
            reader.readAsDataURL($event.target.files[0]);

            reader.onload = () => {
                item.img = reader.result;
                if (uploadInstantly) {
                    this.put(item).then((response) => {
                        item.img = response.img;
                    });
                }
            };
        }
    }

    post(item) {
        return this.testimonialService.post(item).then((item) => {
            this.testimonials.push(item);
            this.newTestimonial = new Testimonial();
        });
    }

    put(item) {
        return this.testimonialService.put(item);
    }

    patch(item) {
       return this.testimonialService.patch(item);
    }

    delete(item) {
        return this.testimonialService.delete(item.id).then(() => {
            this.testimonials.splice(this.testimonials.indexOf(item), 1);
        });
    }
}
