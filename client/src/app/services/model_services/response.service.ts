import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Response } from '../../models/response';

const responses: Response[] = [
    { id: 0, response: 'Pending' },
    { id: 1, response: 'Accepted' },
    { id: 2, response: 'Forbidden Word in Source' },
    { id: 3, response: 'Undefined File Type' },
    { id: 4, response: 'Compile Error' },
    { id: 5, response: 'Exceeds Time Limit' },
    { id: 6, response: 'Incorrect Output' },
    { id: 7, response: 'Format Error' },
    { id: 8, response: 'Runtime Error' },
    { id: 9, response: 'Error (Reason Unknown)' }
];

@Injectable()
export class ResponseService {
    constructor(private baseService: BaseService) { }

    getMockResponses() {
        return responses;
    }

    getResponses() {
        return this.baseService.get('');
    }
}