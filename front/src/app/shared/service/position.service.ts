import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class PositionService {

  constructor() { }

  setPosition(lat, lng ) {
    let object = { lat, lng };
    window.localStorage.setItem('position', JSON.stringify(object));
  }

  getPosition() {
    try {
      return JSON.parse(window.localStorage.getItem('position'));
    } catch(e) {
      return null;
    }
  }
}
