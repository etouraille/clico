import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class UtilsService {

  constructor() { }

  parseJwt (token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
      return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
    return JSON.parse(jsonPayload);
  };

  reorderRemove( data: any[], removedAt: number ): any[] {
    return data.map((elem: any, index: number) => {
      if(index>= removedAt) {
          return { ...elem, rank: elem.rank - 1}
      } else {
        return elem;
      }
    })
  }

  reorderMove( data: any[], previousIndex: number, currentIndex: number): any[] {
    // apres
    // position 1 passe a position 2
    // [{rouge, 0}{bleu , 1 }{vert, 2}{organge, 3}{jaune,4}]
    // [{rouge, 0}}{vert, 2}{organge, 3}{bleu , 1 }{jaune4}]
    if(currentIndex>previousIndex) {
      let lastRank = null;
      for(let i = previousIndex;i<currentIndex;i++) {
        lastRank = data[i].rank;
        data[i].rank --;
      }
      data[currentIndex].rank = lastRank;
    }
    if(currentIndex<previousIndex) {
      let firstRank = null;
      for(let i = previousIndex;i>currentIndex;i--) {
        firstRank = data[i].rank;
        data[i].rank ++;
      }
      data[currentIndex].rank = firstRank;
    }
    return data;
  }
}
