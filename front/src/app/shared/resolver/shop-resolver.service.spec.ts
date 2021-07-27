import { TestBed } from '@angular/core/testing';

import { ShopResolverService } from './shop-resolver.service';

describe('ShopResolverService', () => {
  let service: ShopResolverService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ShopResolverService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
