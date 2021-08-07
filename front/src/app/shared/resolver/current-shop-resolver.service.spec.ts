import { TestBed } from '@angular/core/testing';

import { CurrentShopResolverService } from './current-shop-resolver.service';

describe('CurrentShopResolverService', () => {
  let service: CurrentShopResolverService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(CurrentShopResolverService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
