// SPDX-FileCopyrightText: Sami Finnilä <sami.finnila@nextcloud.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

import Vue from 'vue'
import Text2ImageHelperGenerationPage from './views/Text2ImageHelperGenerationPage.vue'
import { loadState } from '@nextcloud/initial-state'

Vue.mixin({ methods: { t, n } })
const options = loadState('text2image_helper', 'generation-page-inputs')
const imageGenId = options.image_gen_id
const forceEditMode = options.force_edit_mode
const View = Vue.extend(Text2ImageHelperGenerationPage)
new View({ propsData: { imageGenId, forceEditMode } }).$mount('#text2image_helper_generation_page')
