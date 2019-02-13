import {Injectable} from '@angular/core';
import {Http, Headers} from '@angular/http';
import 'rxjs/add/operator/toPromise';
import 'rxjs/add/operator/catch';

import {Testimonial, ApiTestimonial} from '../models/testimonial';

@Injectable()
export class TestimonialService {

    // private urlPrefix = location.protocol + '//' + location.hostname + '/';
    private apiUrl = 'http://localhost/api/admin/testimonials';
    private token = window['localStorage'].getItem('token');
    private headers = new Headers({Authorization: this.token});

    constructor(private http: Http) {}

    get(): Promise<Testimonial[]> {
        return this.http
            .get(this.apiUrl + '?api_token=ssss', {headers: this.headers})
            .toPromise()
            .then(response => {
                const list: Array<Testimonial> = [];
                response.json().forEach(item => {
                    list.push(new Testimonial(item));
                });
                return list;
            })
            .catch(this.handleError);
    }


    post(testimonial: Testimonial): Promise<any> {
        const apiTestimonial = new ApiTestimonial(testimonial);
        return this.http
            .post(this.apiUrl + '?api_token=ssss', apiTestimonial, {headers: this.headers})
            .toPromise()
            .then(response => new Testimonial(response.json()))
            .catch(this.handleError);
    }


    put(testimonial: Testimonial): Promise<any> {
        const apiTestimonial = new ApiTestimonial(testimonial);
        return this.http
            .put(this.apiUrl + '?api_token=ssss', apiTestimonial, {headers: this.headers})
            .toPromise()
            .then(response => new Testimonial(response.json()))
            .catch(this.handleError);
    }


    patch(testimonial: Testimonial): Promise<any> {
        return this.http
            .patch(this.apiUrl + '?api_token=ssss', {headers: this.headers})
            .toPromise()
            .then(response => new Testimonial(response.json()))
            .catch(this.handleError);
    }


    delete(id: number): Promise<any> {
        return this.http
            .delete(this.apiUrl + '/' + id + '?api_token=ssss', {headers: this.headers})
            .toPromise()
            .catch(this.handleError);
    }

    
    private handleError(error: any): Promise<any> {
        console.error('An error occurred', error); // TODO aler message
        return Promise.reject(error.message || error);
    }

}
