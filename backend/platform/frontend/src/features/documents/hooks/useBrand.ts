import {
  useMemo,
  useState,
} from 'react'

import BrandService
from '../services/BrandService'

export default function useBrand() {

  const themes =
    useMemo(
      () =>
        BrandService.all(),
      [],
    )

  const [themeId, setThemeId] =
    useState(
      themes[0].id,
    )

  const theme =
    BrandService.get(
      themeId,
    )!

  return {

    themes,

    theme,

    themeId,

    setThemeId,

  }

}