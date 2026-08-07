import {
  DEFAULT_THEMES,
} from '../constants/themes'

export default class BrandService {

  static all() {

    return DEFAULT_THEMES

  }

  static get(
    id: string,
  ) {

    return DEFAULT_THEMES.find(
      theme =>
        theme.id === id,
    )

  }

}