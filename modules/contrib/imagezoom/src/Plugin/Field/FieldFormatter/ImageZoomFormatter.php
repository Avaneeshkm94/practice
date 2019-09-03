<?php

namespace Drupal\imagezoom\Plugin\Field\FieldFormatter;

use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Image Zoom field formatter for Image fields.
 *
 * @FieldFormatter(
 *  id = "imagezoom",
 *  label = @Translation("Image Zoom"),
 *  field_types = {
 *     "image"
 *   }
 * )
 */
class ImageZoomFormatter extends ImageFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'imagezoom_zoom_type' => '',
      'imagezoom_display_style' => '',
      'imagezoom_zoom_style' => '',
      'imagezoom_disable' => '',
      'imagezoom_disable_width' => '',
      'imagezoom_additional' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['imagezoom_zoom_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Zoom type'),
      '#options' => $this->zoomTypes(),
      '#default_value' => $this->getSetting('imagezoom_zoom_type'),
    ];

    $image_styles = image_style_options(FALSE);
    $element['imagezoom_display_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Image style'),
      '#options' => $image_styles,
      '#empty_option' => $this->t('None (original image)'),
      '#default_value' => $this->getSetting('imagezoom_display_style'),
    ];

    $element['imagezoom_zoom_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Zoomed Image style'),
      '#options' => $image_styles,
      '#empty_option' => $this->t('None (original image)'),
      '#default_value' => $this->getSetting('imagezoom_zoom_style'),
    ];

    $element['imagezoom_disable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disable zoom on small screens'),
      '#return_value' => 1,
      '#default_value' => $this->getSetting('imagezoom_disable'),
    ];

    $element['imagezoom_disable_width'] = [
      '#type' => 'number',
      '#title' => $this->t('Minimum width for zoom to display'),
      '#min' => 0,
      '#states' => [
        'invisible' => [
          ':input[name="fields[field_image][settings_edit_form][settings][imagezoom_disable]"]' => [
            'checked' => FALSE,
          ],
        ],
      ],
      '#default_value' => $this->getSetting('imagezoom_disable_width'),
    ];

    $docs = Link::fromTextAndUrl(
      $this->t('documentation'),
      Url::fromUri('http://igorlino.github.io/elevatezoom-plus/api.htm')
    );
    $element['imagezoom_additional'] = [
      '#type' => 'textarea',
      '#title' => t('Additional settings'),
      '#element_validate' => [
        [$this, 'additionalSettingsValidate'],
      ],
      '#description' => $this->t('Add additional settings. For a list of available options, see the @docs. Settings should be added in the following format: <pre>@code</pre>', [
        '@docs' => $docs->toString(),
        '@code' => 'option: value',
      ]),
      '#default_value' => $this->getSetting('imagezoom_additional'),
    ];

    return $element;
  }

  /**
   * Validate additional settings.
   */
  public function additionalSettingsValidate($element, FormStateInterface $form_state) {
    $settings_array = explode("\n", $element['#value']);
    foreach ($settings_array as $setting) {
      if (!empty($setting)) {
        if (!preg_match('/^[a-z][a-z0-9-_]*: ?[a-z][a-z0-9-_]*$/i', trim($setting))) {
          $form_state->setErrorByName('imagezoom_additional', t('Additional settings must be in the format "option: value".'));
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $zoom_types = $this->zoomTypes();
    $summary[] = $this->t('Zoom type: @type', [
      '@type' => $zoom_types[$this->getSetting('imagezoom_zoom_type')],
    ]);

    $image_styles = image_style_options(FALSE);
    unset($image_styles['']);
    $summary[] = $this->t('Display image style: @style', [
      '@style' => isset($image_styles[$this->getSetting('imagezoom_display_style')]) ?
      $image_styles[$this->getSetting('imagezoom_display_style')] : 'original',
    ]);
    $summary[] = $this->t('Zoomed image style: @style', [
      '@style' => isset($image_styles[$this->getSetting('imagezoom_zoom_style')]) ?
      $image_styles[$this->getSetting('imagezoom_zoom_style')] : 'original',
    ]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $settings = [
      'zoomType' => $this->getSetting('imagezoom_zoom_type'),
    ];

    if ($this->getSetting('imagezoom_disable')) {
      $settings['responsive'] = TRUE;
      $settings['respond'] = [
        [
          'range' => '0 - ' . $this->getSetting('imagezoom_disable_width'),
          'enabled' => FALSE,
        ],
      ];
    }

    $additonal_settings = $this->settingsToArray($this->getSetting('imagezoom_additional'));
    $settings += $additonal_settings;

    \Drupal::moduleHandler()->alter('imagezoom_settings', $settings);

    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#theme' => 'imagezoom_image',
        '#item' => $item,
        '#display_style' => $this->getSetting('imagezoom_display_style'),
        '#zoom_style' => $this->getSetting('imagezoom_zoom_style'),
        '#settings' => $settings,
      ];
    }

    $elements['#attached'] = [
      'library' => [
        'imagezoom/elevatezoom',
      ],
      'drupalSettings' => [
        'imagezoom' => $settings,
      ],
    ];

    return $elements;
  }

  /**
   * Returns an array of available zoom types.
   */
  protected function zoomTypes() {
    $types = [
      'window' => $this->t('Window'),
      'inner' => $this->t('Inner'),
      'lens' => $this->t('Lens'),
    ];

    return $types;
  }

  /**
   * Convert a settings string to an array.
   */
  protected function settingsToArray($string) {
    $settings = [];

    if (!empty($string)) {
      $array = explode("\n", $string);

      foreach ($array as $option) {
        $parts = explode(':', $option);
        if (count($parts) == 2) {
          $key = trim($parts[0]);
          $value = trim($parts[1]);
          $settings[$key] = $value;
        }
      }
    }

    return $settings;
  }

}
