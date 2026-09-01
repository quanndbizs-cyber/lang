<?php

/**
 * Built-in Lightweight Curriculum & Reference Data for:
 * - English (Cambridge A2 Key Essential Vocab & Structures)
 * - Chinese (Pinyin Chart, HSK 1-3 Core Vocab & Grammar)
 * - Japanese (Hiragana, Katakana, JLPT N5 Vocab & Kanji)
 * - Korean (Hangul Alphabet, TOPIK I Core Vocab & Expressions)
 */

function get_pinyin_data(): array
{
    return [
        'initials' => [
            ['p' => 'b', 'ipa' => 'b', 'desc' => 'như p tiếng Việt (bà -> p)'],
            ['p' => 'p', 'ipa' => 'pʰ', 'desc' => 'bật hơi mạnh (ph)'],
            ['p' => 'm', 'ipa' => 'm', 'desc' => 'như m tiếng Việt'],
            ['p' => 'f', 'ipa' => 'f', 'desc' => 'như ph/f tiếng Việt'],
            ['p' => 'd', 'ipa' => 't', 'desc' => 'như t tiếng Việt'],
            ['p' => 't', 'ipa' => 'tʰ', 'desc' => 'như th tiếng Việt, bật hơi'],
            ['p' => 'n', 'ipa' => 'n', 'desc' => 'như n tiếng Việt'],
            ['p' => 'l', 'ipa' => 'l', 'desc' => 'như l tiếng Việt'],
            ['p' => 'g', 'ipa' => 'k', 'desc' => 'như c/k tiếng Việt'],
            ['p' => 'k', 'ipa' => 'kʰ', 'desc' => 'như kh tiếng Việt, bật hơi'],
            ['p' => 'h', 'ipa' => 'x', 'desc' => 'như h/kh nhẹ'],
            ['p' => 'j', 'ipa' => 'tɕ', 'desc' => 'mặt lưỡi, như ch/gi nhẹ'],
            ['p' => 'q', 'ipa' => 'tɕʰ', 'desc' => 'mặt lưỡi bật hơi mạnh'],
            ['p' => 'x', 'ipa' => 'ɕ', 'desc' => 'như x tiếng Việt'],
            ['p' => 'zh', 'ipa' => 'ʈʂ', 'desc' => 'uốn lưỡi, như tr tiếng Việt'],
            ['p' => 'ch', 'ipa' => 'ʈʂʰ', 'desc' => 'uốn lưỡi + bật hơi'],
            ['p' => 'sh', 'ipa' => 'ʂ', 'desc' => 'uốn lưỡi, như s tiếng Việt'],
            ['p' => 'r', 'ipa' => 'ʐ', 'desc' => 'uốn lưỡi, như r tiếng Việt'],
            ['p' => 'z', 'ipa' => 'ts', 'desc' => 'đầu lưỡi thẳng, như ch/tz'],
            ['p' => 'c', 'ipa' => 'tsʰ', 'desc' => 'đầu lưỡi thẳng + bật hơi'],
            ['p' => 's', 'ipa' => 's', 'desc' => 'đầu lưỡi thẳng, như s/x'],
        ],
        'finals' => [
            ['p' => 'a', 'ipa' => 'a', 'desc' => 'a'],
            ['p' => 'o', 'ipa' => 'o', 'desc' => 'ô / uô'],
            ['p' => 'e', 'ipa' => 'ɤ', 'desc' => 'ưa / ơ'],
            ['p' => 'i', 'ipa' => 'i', 'desc' => 'i (hoặc ư sau z, c, s, zh, ch, sh, r)'],
            ['p' => 'u', 'ipa' => 'u', 'desc' => 'u'],
            ['p' => 'ü', 'ipa' => 'y', 'desc' => 'uy (tròn môi)'],
            ['p' => 'ai', 'ipa' => 'ai', 'desc' => 'ai'],
            ['p' => 'ei', 'ipa' => 'ei', 'desc' => 'ây'],
            ['p' => 'ao', 'ipa' => 'au', 'desc' => 'ao'],
            ['p' => 'ou', 'ipa' => 'ou', 'desc' => 'âu / ôu'],
            ['p' => 'an', 'ipa' => 'an', 'desc' => 'an'],
            ['p' => 'en', 'ipa' => 'ən', 'desc' => 'ân'],
            ['p' => 'ang', 'ipa' => 'aŋ', 'desc' => 'ang'],
            ['p' => 'eng', 'ipa' => 'əŋ', 'desc' => 'âng'],
            ['p' => 'er', 'ipa' => 'aɚ', 'desc' => 'ơ (uốn lưỡi)'],
        ],
        'tones' => [
            ['tone' => 'Thanh 1 (Âm Bình)', 'mark' => 'ā', 'pitch' => '55', 'desc' => 'Cao bằng, ngân đều: mā (mẹ)'],
            ['tone' => 'Thanh 2 (Dương Bình)', 'mark' => 'á', 'pitch' => '35', 'desc' => 'Từ trung bình lên cao (như dấu sắc): má (gai)'],
            ['tone' => 'Thanh 3 (Thượng Thanh)', 'mark' => 'ǎ', 'pitch' => '214', 'desc' => 'Xuống thấp rồi lên nhẹ (như hỏi+ngã): mǎ (ngựa)'],
            ['tone' => 'Thanh 4 (Khứ Thanh)', 'mark' => 'à', 'pitch' => '51', 'desc' => 'Từ cao rơi mạnh dứt khoát: mà (mắng)'],
            ['tone' => 'Thanh nhẹ (Khinh Thanh)', 'mark' => 'ma', 'pitch' => '--', 'desc' => 'Đọc ngắn nhẹ: ma (trợ từ)'],
        ]
    ];
}

function get_kana_data(): array
{
    return [
        'hiragana' => [
            ['h' => 'あ', 'r' => 'a', 'k' => 'ア'], ['h' => 'い', 'r' => 'i', 'k' => 'イ'], ['h' => 'う', 'r' => 'u', 'k' => 'ウ'], ['h' => 'え', 'r' => 'e', 'k' => 'エ'], ['h' => 'お', 'r' => 'o', 'k' => 'オ'],
            ['h' => 'か', 'r' => 'ka', 'k' => 'カ'], ['h' => 'き', 'r' => 'ki', 'k' => 'キ'], ['h' => 'く', 'r' => 'ku', 'k' => 'ク'], ['h' => 'け', 'r' => 'ke', 'k' => 'ケ'], ['h' => 'こ', 'r' => 'ko', 'k' => 'コ'],
            ['h' => 'さ', 'r' => 'sa', 'k' => 'サ'], ['h' => 'し', 'r' => 'shi', 'k' => 'シ'], ['h' => 'す', 'r' => 'su', 'k' => 'ス'], ['h' => 'せ', 'r' => 'se', 'k' => 'セ'], ['h' => 'そ', 'r' => 'so', 'k' => 'ソ'],
            ['h' => 'た', 'r' => 'ta', 'k' => 'タ'], ['h' => 'ち', 'r' => 'chi', 'k' => 'チ'], ['h' => 'つ', 'r' => 'tsu', 'k' => 'ツ'], ['h' => 'て', 'r' => 'te', 'k' => 'テ'], ['h' => 'と', 'r' => 'to', 'k' => 'ト'],
            ['h' => 'な', 'r' => 'na', 'k' => 'ナ'], ['h' => 'に', 'r' => 'ni', 'k' => 'ニ'], ['h' => 'ぬ', 'r' => 'nu', 'k' => 'ヌ'], ['h' => 'ね', 'r' => 'ne', 'k' => 'ネ'], ['h' => 'の', 'r' => 'no', 'k' => 'ノ'],
            ['h' => 'は', 'r' => 'ha', 'k' => 'ハ'], ['h' => 'ひ', 'r' => 'hi', 'k' => 'ヒ'], ['h' => 'ふ', 'r' => 'fu', 'k' => 'フ'], ['h' => 'へ', 'r' => 'he', 'k' => 'ヘ'], ['h' => 'ほ', 'r' => 'ho', 'k' => 'ホ'],
            ['h' => 'ま', 'r' => 'ma', 'k' => 'マ'], ['h' => 'み', 'r' => 'mi', 'k' => 'ミ'], ['h' => 'む', 'r' => 'mu', 'k' => 'ム'], ['h' => 'め', 'r' => 'me', 'k' => 'メ'], ['h' => 'も', 'r' => 'mo', 'k' => 'モ'],
            ['h' => 'や', 'r' => 'ya', 'k' => 'ヤ'], ['h' => '', 'r' => '', 'k' => ''], ['h' => 'ゆ', 'r' => 'yu', 'k' => 'ユ'], ['h' => '', 'r' => '', 'k' => ''], ['h' => 'よ', 'r' => 'yo', 'k' => 'ヨ'],
            ['h' => 'ら', 'r' => 'ra', 'k' => 'ラ'], ['h' => 'り', 'r' => 'ri', 'k' => 'リ'], ['h' => 'る', 'r' => 'ru', 'k' => 'ル'], ['h' => 'れ', 'r' => 're', 'k' => 'レ'], ['h' => 'ろ', 'r' => 'ro', 'k' => 'ロ'],
            ['h' => 'わ', 'r' => 'wa', 'k' => 'ワ'], ['h' => '', 'r' => '', 'k' => ''], ['h' => '', 'r' => '', 'k' => ''], ['h' => '', 'r' => '', 'k' => ''], ['h' => 'を', 'r' => 'wo', 'k' => 'ヲ'],
            ['h' => 'ん', 'r' => 'n', 'k' => 'ン'],
        ],
        'dakuten' => [
            ['h' => 'が', 'r' => 'ga'], ['h' => 'ぎ', 'r' => 'gi'], ['h' => 'ぐ', 'r' => 'gu'], ['h' => 'げ', 'r' => 'ge'], ['h' => 'ご', 'r' => 'go'],
            ['h' => 'ざ', 'r' => 'za'], ['h' => 'じ', 'r' => 'ji'], ['h' => 'ず', 'r' => 'zu'], ['h' => 'ぜ', 'r' => 'ze'], ['h' => 'ぞ', 'r' => 'zo'],
            ['h' => 'だ', 'r' => 'da'], ['h' => 'ぢ', 'r' => 'ji/dji'], ['h' => 'づ', 'r' => 'zu/dzu'], ['h' => 'で', 'r' => 'de'], ['h' => 'ど', 'r' => 'do'],
            ['h' => 'ば', 'r' => 'ba'], ['h' => 'び', 'r' => 'bi'], ['h' => 'ぶ', 'r' => 'bu'], ['h' => 'べ', 'r' => 'be'], ['h' => 'ぼ', 'r' => 'bo'],
            ['h' => 'ぱ', 'r' => 'pa'], ['h' => 'ぴ', 'r' => 'pi'], ['h' => 'ぷ', 'r' => 'pu'], ['h' => 'ぺ', 'r' => 'pe'], ['h' => 'ぽ', 'r' => 'po'],
        ]
    ];
}

function get_hangul_data(): array
{
    return [
        'vowels' => [
            ['c' => 'ㅏ', 'r' => 'a', 'desc' => 'a (như ba)'],
            ['c' => 'ㅑ', 'r' => 'ya', 'desc' => 'ya'],
            ['c' => 'ㅓ', 'r' => 'eo', 'desc' => 'ơ / o (mở rộng miệng)'],
            ['c' => 'ㅕ', 'r' => 'yeo', 'desc' => 'yơ / yo'],
            ['c' => 'ㅗ', 'r' => 'o', 'desc' => 'ô (tròn môi)'],
            ['c' => 'ㅛ', 'r' => 'yo', 'desc' => 'yô'],
            ['c' => 'ㅜ', 'r' => 'u', 'desc' => 'u (tròn môi)'],
            ['c' => 'ㅠ', 'r' => 'yu', 'desc' => 'yu'],
            ['c' => 'ㅡ', 'r' => 'eu', 'desc' => 'ư (dẹt miệng)'],
            ['c' => 'ㅣ', 'r' => 'i', 'desc' => 'i'],
            ['c' => 'ㅐ', 'r' => 'ae', 'desc' => 'e / ae'],
            ['c' => 'ㅔ', 'r' => 'e', 'desc' => 'ê / e'],
            ['c' => 'ㅘ', 'r' => 'wa', 'desc' => 'oa'],
            ['c' => 'ㅝ', 'r' => 'wo', 'desc' => 'uơ'],
            ['c' => 'ㅟ', 'r' => 'wi', 'desc' => 'uy'],
            ['c' => 'ㅢ', 'r' => 'ui', 'desc' => 'ưi'],
        ],
        'consonants' => [
            ['c' => 'ㄱ', 'r' => 'g/k', 'desc' => 'khi đứng đầu là k, giữa từ là g'],
            ['c' => 'ㄴ', 'r' => 'n', 'desc' => 'âm n'],
            ['c' => 'ㄷ', 'r' => 'd/t', 'desc' => 'khi đứng đầu là t, giữa từ là d'],
            ['c' => 'ㄹ', 'r' => 'r/l', 'desc' => 'đầu từ là r, cuối âm là l'],
            ['c' => 'ㅁ', 'r' => 'm', 'desc' => 'âm m'],
            ['c' => 'ㅂ', 'r' => 'b/p', 'desc' => 'đầu từ là p, giữa từ là b'],
            ['c' => 'ㅅ', 'r' => 's', 'desc' => 'âm s nhẹ (với i đọc là sh)'],
            ['c' => 'ㅇ', 'r' => 'ng / silent', 'desc' => 'đầu từ là câm (chỉ đệm âm), cuối từ là ng'],
            ['c' => 'ㅈ', 'r' => 'j/ch', 'desc' => 'âm ch nhẹ / j'],
            ['c' => 'ㅊ', 'r' => 'ch', 'desc' => 'bật hơi mạnh'],
            ['c' => 'ㅋ', 'r' => 'k', 'desc' => 'bật hơi mạnh'],
            ['c' => 'ㅌ', 'r' => 't', 'desc' => 'bật hơi mạnh'],
            ['c' => 'ㅍ', 'r' => 'p', 'desc' => 'bật hơi mạnh'],
            ['c' => 'ㅎ', 'r' => 'h', 'desc' => 'âm h'],
            ['c' => 'ㄲ', 'r' => 'kk', 'desc' => 'âm căng k'],
            ['c' => 'ㄸ', 'r' => 'tt', 'desc' => 'âm căng t'],
            ['c' => 'ㅃ', 'r' => 'pp', 'desc' => 'âm căng p'],
            ['c' => 'ㅆ', 'r' => 'ss', 'desc' => 'âm căng s'],
            ['c' => 'ㅉ', 'r' => 'jj', 'desc' => 'âm căng ch/j'],
        ]
    ];
}

function get_language_vocab_packs(): array
{
    return [
        'en' => [
            [
                'category' => 'A2 Key Essential (Daily Life & School)',
                'level' => 'A2',
                'items' => [
                    ['word' => 'accommodation', 'ipa' => '/əˌkɒm.əˈdeɪ.ʃən/', 'meaning' => 'chỗ ở, nơi trọ', 'example' => 'We need to find cheap accommodation near the university.'],
                    ['word' => 'appointment', 'ipa' => '/əˈpɔɪnt.mənt/', 'meaning' => 'cuộc hẹn', 'example' => 'I have an appointment with the dentist at 3 PM.'],
                    ['word' => 'attraction', 'ipa' => '/əˈtræk.ʃən/', 'meaning' => 'điểm thu hút du lịch', 'example' => 'The castle is the main tourist attraction in our town.'],
                    ['word' => 'available', 'ipa' => '/əˈveɪ.lə.bəl/', 'meaning' => 'có sẵn, rảnh rỗi', 'example' => 'Are tickets available for tomorrow night?'],
                    ['word' => 'borrow', 'ipa' => '/ˈbɒr.əʊ/', 'meaning' => 'mượn', 'example' => 'Can I borrow your English dictionary for a minute?'],
                    ['word' => 'celebrate', 'ipa' => '/ˈsel.ə.breɪt/', 'meaning' => 'ăn mừng, kỷ niệm', 'example' => 'We are going to celebrate my brother’s birthday tomorrow.'],
                    ['word' => 'competition', 'ipa' => '/ˌkɒm.pəˈtɪʃ.ən/', 'meaning' => 'cuộc thi', 'example' => 'She won first prize in the photography competition.'],
                    ['word' => 'delicious', 'ipa' => '/dɪˈlɪʃ.əs/', 'meaning' => 'ngon miệng', 'example' => 'This noodle soup is absolutely delicious.'],
                    ['word' => 'equipment', 'ipa' => '/ɪˈkwɪp.mənt/', 'meaning' => 'trang thiết bị', 'example' => 'You need special equipment for rock climbing.'],
                    ['word' => 'invitation', 'ipa' => '/ˌɪn.vɪˈteɪ.ʃən/', 'meaning' => 'lời mời, thiệp mời', 'example' => 'Thanks for the invitation to your concert!'],
                    ['word' => 'opportunity', 'ipa' => '/ˌɒp.əˈtjuː.nə.ti/', 'meaning' => 'cơ hội', 'example' => 'Studying abroad is a great opportunity to learn.'],
                    ['word' => 'recommend', 'ipa' => '/ˌrek.əˈmend/', 'meaning' => 'gợi ý, khuyên dùng', 'example' => 'Can you recommend a good restaurant nearby?'],
                ]
            ],
            [
                'category' => 'A2 Key Travel & Transport',
                'level' => 'A2-B1',
                'items' => [
                    ['word' => 'delay', 'ipa' => '/dɪˈleɪ/', 'meaning' => 'trì hoãn, chậm trễ', 'example' => 'The flight had a two-hour delay due to bad weather.'],
                    ['word' => 'destination', 'ipa' => '/ˌdes.tɪˈneɪ.ʃən/', 'meaning' => 'điểm đến', 'example' => 'Da Nang is our favorite holiday destination.'],
                    ['word' => 'luggage', 'ipa' => '/ˈlʌɡ.ɪdʒ/', 'meaning' => 'hành lý', 'example' => 'Do not leave your luggage unattended at the station.'],
                    ['word' => 'passenger', 'ipa' => '/ˈpæs.ən.dʒər/', 'meaning' => 'hành khách', 'example' => 'All passengers must fasten their seatbelts.'],
                    ['word' => 'platform', 'ipa' => '/ˈplæt.fɔːm/', 'meaning' => 'sân ga, thềm ga', 'example' => 'The train to London departs from platform 4.'],
                ]
            ]
        ],
        'zh' => [
            [
                'category' => 'HSK 1 - Chào hỏi & Giao tiếp cơ bản',
                'level' => 'HSK 1',
                'items' => [
                    ['word' => '你好', 'ipa' => 'Nǐ hǎo', 'meaning' => 'Xin chào (Hán Việt: Nhĩ Hảo)', 'example' => '你好！很高兴认识你。(Nǐ hǎo! Hěn gāoxìng rènshí nǐ.)'],
                    ['word' => '谢谢', 'ipa' => 'Xièxie', 'meaning' => 'Cảm ơn (Hán Việt: Tạ Tạ)', 'example' => '非常感谢你的帮助！(Fēicháng gǎnxiè nǐ de bāngzhù!)'],
                    ['word' => '不客气', 'ipa' => 'Bù kèqi', 'meaning' => 'Không có chi / Đừng khách sáo', 'example' => '不客气，这是我应该做的。(Bù kèqi, zhè shì wǒ yīnggāi zuò de.)'],
                    ['word' => '再见', 'ipa' => 'Zàijiàn', 'meaning' => 'Tạm biệt (Hán Việt: Tái Kiến)', 'example' => '明天见，再见！(Míngtiān jiàn, zàijiàn!)'],
                    ['word' => '学习', 'ipa' => 'Xuéxí', 'meaning' => 'Học tập (Hán Việt: Học Tập)', 'example' => '我在学习汉语。(Wǒ zài xuéxí hànyǔ.)'],
                    ['word' => '汉语', 'ipa' => 'Hànyǔ', 'meaning' => 'Tiếng Hán / Tiếng Trung', 'example' => '汉语很有趣。(Hànyǔ hěn yǒuqù.)'],
                    ['word' => '朋友', 'ipa' => 'Péngyou', 'meaning' => 'Bạn bè (Hán Việt: Bằng Hữu)', 'example' => '他是我的好朋友。(Tā shì wǒ de hǎo péngyou.)'],
                    ['word' => '多少钱', 'ipa' => 'Duōshao qián', 'meaning' => 'Bao nhiêu tiền?', 'example' => '这个苹果多少钱一斤？(Zhège píngguǒ duōshao qián yī jīn?)'],
                ]
            ],
            [
                'category' => 'HSK 2 & 3 - Đời sống & Du lịch',
                'level' => 'HSK 2-3',
                'items' => [
                    ['word' => '高兴', 'ipa' => 'Gāoxìng', 'meaning' => 'Vui mừng (Cao Hứng)', 'example' => '今天大家都很高兴。(Jīntiān dàjiā dōu hěn gāoxìng.)'],
                    ['word' => '机场', 'ipa' => 'Jīchǎng', 'meaning' => 'Sân bay (Cơ Trường)', 'example' => '我们去机场接朋友。(Wǒmen qù jīchǎng jiē péngyou.)'],
                    ['word' => '准备', 'ipa' => 'Zhǔnbèi', 'meaning' => 'Chuẩn bị (Chuẩn Bị)', 'example' => '你准备好考试了吗？(Nǐ zhǔnbèi hǎo kǎoshì le ma?)'],
                    ['word' => '帮助', 'ipa' => 'Bāngzhù', 'meaning' => 'Giúp đỡ (Bang Trợ)', 'example' => '谢谢你的热情帮助。(Xièxie nǐ de rèqíng bāngzhù.)'],
                ]
            ]
        ],
        'ja' => [
            [
                'category' => 'JLPT N5 - Chào hỏi & Sinh hoạt thường ngày',
                'level' => 'N5',
                'items' => [
                    ['word' => 'こんにちは', 'ipa' => 'Konnichiwa', 'meaning' => 'Xin chào (ban ngày)', 'example' => 'こんにちは、お元気ですか。(Konnichiwa, ogenki desu ka.)'],
                    ['word' => 'ありがとう', 'ipa' => 'Arigatou', 'meaning' => 'Cảm ơn', 'example' => 'どうもありがとうございます。(Doumo arigatou gozaimasu.)'],
                    ['word' => '先生', 'ipa' => 'Sensei (Tiên Sinh)', 'meaning' => 'Thầy cô giáo / Bác sĩ', 'example' => '日本語の先生です。(Nihongo no sensei desu.)'],
                    ['word' => '日本語', 'ipa' => 'Nihongo (Nhật Bản Ngữ)', 'meaning' => 'Tiếng Nhật', 'example' => '毎日日本語を勉強します。(Mainichi nihongo o benkyou shimasu.)'],
                    ['word' => '食べる', 'ipa' => 'Taberu (Thực)', 'meaning' => 'Ăn', 'example' => '朝ごはんを食べます。(Asagohan o tabemasu.)'],
                    ['word' => '飲む', 'ipa' => 'Nomu (Ẩm)', 'meaning' => 'Uống', 'example' => 'お茶を飲みます。(Ocha o nomimasu.)'],
                    ['word' => '友達', 'ipa' => 'Tomodachi (Hữu Đạt)', 'meaning' => 'Bạn bè', 'example' => '友達と映画を見ました。(Tomodachi to eiga o mimashita.)'],
                    ['word' => '行く', 'ipa' => 'Iku (Hành)', 'meaning' => 'Đi', 'example' => '学校へ行きます。(Gakkou e ikimasu.)'],
                ]
            ],
            [
                'category' => 'JLPT N5 - N4 - Kanji Cơ Bản & Mẫu Câu',
                'level' => 'N5-N4',
                'items' => [
                    ['word' => '約束', 'ipa' => 'Yakusoku (Ước Thúc)', 'meaning' => 'Lời hứa / Cuộc hẹn', 'example' => '友達と約束があります。(Tomodachi to yakusoku ga arimasu.)'],
                    ['word' => '大丈夫', 'ipa' => 'Daijoubu (Đại Trượng Phu)', 'meaning' => 'Ổn / Không sao', 'example' => '大丈夫ですから、心配しないで。(Daijoubu desu kara, shinpai shinaide.)'],
                    ['word' => '便利', 'ipa' => 'Benri (Tiện Lợi)', 'meaning' => 'Tiện lợi', 'example' => '地下鉄はとても便利です。(Chikatetsu wa totemo benri desu.)'],
                ]
            ]
        ],
        'ko' => [
            [
                'category' => 'TOPIK I - Chào hỏi & Giao tiếp thông dụng',
                'level' => 'Sơ cấp 1',
                'items' => [
                    ['word' => '안녕하세요', 'ipa' => 'An-nyeong-ha-se-yo', 'meaning' => 'Xin chào (lịch sự)', 'example' => '안녕하세요! 반갑습니다. (Xin chào! Rất vui được gặp bạn.)'],
                    ['word' => '감사합니다', 'ipa' => 'Gam-sa-ham-ni-da', 'meaning' => 'Cảm ơn (trang trọng)', 'example' => '도와주셔서 정말 감사합니다. (Thật lòng cảm ơn vì đã giúp đỡ.)'],
                    ['word' => '죄송합니다', 'ipa' => 'Joe-song-ham-ni-da', 'meaning' => 'Xin lỗi (trang trọng)', 'example' => '늦어서 죄송합니다. (Xin lỗi vì tôi đến muộn.)'],
                    ['word' => '한국어', 'ipa' => 'Han-guk-eo', 'meaning' => 'Tiếng Hàn Quốc', 'example' => '저는 한국어를 배워요. (Tôi học tiếng Hàn.)'],
                    ['word' => '친구', 'ipa' => 'Chin-gu', 'meaning' => 'Bạn bè', 'example' => '친구와 같이 밥을 먹어요. (Ăn cơm cùng bạn.)'],
                    ['word' => '사랑해요', 'ipa' => 'Sa-rang-hae-yo', 'meaning' => 'Tôi yêu bạn', 'example' => '가족을 많이 사랑해요. (Tôi rất yêu gia đình.)'],
                    ['word' => '맛있어요', 'ipa' => 'Mas-iss-eo-yo', 'meaning' => 'Ngon miệng', 'example' => '이 김치찌개는 정말 맛있어요. (Món canh kim chi này rất ngon.)'],
                    ['word' => '얼마예요', 'ipa' => 'Eol-ma-ye-yo', 'meaning' => 'Bao nhiêu tiền?', 'example' => '이거 얼마예요? (Cái này giá bao nhiêu ạ?)'],
                ]
            ],
            [
                'category' => 'TOPIK I - Động từ & Tính từ cơ bản',
                'level' => 'Sơ cấp 2',
                'items' => [
                    ['word' => '가다 / 와요', 'ipa' => 'Ga-da / Wa-yo', 'meaning' => 'Đi / Đến', 'example' => '집에 가요. 학교에 와요.'],
                    ['word' => '공부하다', 'ipa' => 'Gong-bu-ha-da', 'meaning' => 'Học tập', 'example' => '도서관에서 공부해요. (Học ở thư viện.)'],
                    ['word' => '행복하다', 'ipa' => 'Haeng-bok-ha-da', 'meaning' => 'Hạnh phúc', 'example' => '오늘 정말 행복해요. (Hôm nay tôi rất hạnh phúc.)'],
                ]
            ]
        ]
    ];
}

function get_japanese_grammar_data(array $config = []): array
{
    $n5Items = [
        [
            'stt' => 1,
            'level' => 'N5',
            'pattern' => '～は～です (~ wa ~ desu)',
            'meaning' => 'N1 là N2 (Câu khẳng định danh từ)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-wa-desu/',
            'example' => 'わたしは学生です。(Watashi wa gakusei desu - Tôi là học sinh.)',
            'note' => 'Cấu trúc: [N1] は [N2] です. Trợ từ は đọc là "wa", dùng xác định chủ đề câu.'
        ],
        [
            'stt' => 2,
            'level' => 'N5',
            'pattern' => '～じゃありません / ではありません (~ ja arimasen / dewa arimasen)',
            'meaning' => 'N1 không phải là N2 (Câu phủ định danh từ)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-ja-arimasen/',
            'example' => '田中さんは先生じゃありません。(Tanaka-san wa sensei ja arimasen - Anh Tanaka không phải là giáo viên.)',
            'note' => 'Cấu trúc: [N1] は [N2] じゃありません. Văn nói dùng じゃありません, trang trọng/văn viết dùng ではありません.'
        ],
        [
            'stt' => 3,
            'level' => 'N5',
            'pattern' => '～ですか (~ desu ka)',
            'meaning' => 'Có phải là ... không? (Câu hỏi nghi vấn)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-desu-ka/',
            'example' => 'あの方はマイクさんですか。(Ano kata wa Maiku-san desu ka - Vị kia có phải là anh Mike không?)',
            'note' => 'Cấu trúc: [Mệnh đề] + か. Trợ từ か ở cuối câu tạo thành câu hỏi, không dùng dấu chấm hỏi (?) trong văn bản chuẩn.'
        ],
        [
            'stt' => 4,
            'level' => 'N5',
            'pattern' => '～も (~ mo)',
            'meaning' => '... cũng là ... (Trợ từ "cũng")',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-mo/',
            'example' => 'ミラーさんはアメリカ人です。スミスさんもアメリカ人です。(Miraa-san wa amerikajin desu. Sumisu-san mo amerikajin desu - Anh Miller là người Mỹ. Anh Smith cũng là người Mỹ.)',
            'note' => 'Cấu trúc: [N] も. Dùng thay thế cho trợ từ は/が/を khi muốn biểu thị sự đồng nhất tính chất hoặc hành động.'
        ],
        [
            'stt' => 5,
            'level' => 'N5',
            'pattern' => '～の (~ no)',
            'meaning' => 'Của, thuộc về, về mặt (Trợ từ sở hữu & giải thích)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-no/',
            'example' => 'これは私の本です。(Kore wa watashi no hon desu - Đây là cuốn sách của tôi.)',
            'note' => 'Cấu trúc: [N1] の [N2]. N1 bổ nghĩa cho N2: sở hữu (của ai), chủng loại, xuất xứ.'
        ],
        [
            'stt' => 6,
            'level' => 'N5',
            'pattern' => 'これ / それ / あれ / どれ (kore / sore / are / dore)',
            'meaning' => 'Cái này / Cái đó / Cái kia / Cái nào (Đại từ chỉ vật)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-kore-sore-are/',
            'example' => 'これは辞書です。あれは傘です。(Kore wa jisho desu. Are wa kasa desu - Đây là từ điển. Kia là cái ô.)',
            'note' => 'これ: gần người nói; それ: gần người nghe; あれ: xa cả hai; どれ: từ để hỏi (cái nào).'
        ],
        [
            'stt' => 7,
            'level' => 'N5',
            'pattern' => 'この / その / あの / どの + N (kono / sono / ano / dono + N)',
            'meaning' => '... này / ... đó / ... kia / ... nào (Chỉ định từ bổ nghĩa)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-kono-sono-ano/',
            'example' => 'このカメラは高いです。(Kono kamera wa takai desu - Chiếc máy ảnh này đắt.)',
            'note' => 'Bắt buộc phải đi kèm với danh từ đứng ngay sau: [この/その/あの/どの] + N.'
        ],
        [
            'stt' => 8,
            'level' => 'N5',
            'pattern' => 'ここ / そこ / あそこ / どこ (koko / soko / asoko / doko)',
            'meaning' => 'Ở đây / Ở đó / Ở kia / Ở đâu (Đại từ chỉ nơi chốn)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-koko-soko-asoko/',
            'example' => '受付はどこですか。あそこです。(Uketsuke wa doko desu ka. Asoko desu - Quầy tiếp tân ở đâu ạ? Ở đằng kia ạ.)',
            'note' => 'Chỉ vị trí không gian. Dạng lịch sự tương ứng là: こちら / そちら / あちら / どちら.'
        ],
        [
            'stt' => 9,
            'level' => 'N5',
            'pattern' => 'N (Địa điểm) + へ + 行きます / 来ます / 帰ります (e ikimasu / kimasu / kaerimasu)',
            'meaning' => 'Đi đến / Đến / Trở về [Địa điểm]',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-e-ikimasu/',
            'example' => '明日東京へ行きます。(Ashita Toukyou e ikimasu - Ngày mai tôi đi Tokyo.) / 国へ帰ります。(Kuni e kaerimasu - Về nước.)',
            'note' => 'Cấu trúc: [N nơi chốn] + へ + [V di chuyển]. Trợ từ へ phát âm là "e", chỉ hướng di chuyển.'
        ],
        [
            'stt' => 10,
            'level' => 'N5',
            'pattern' => 'N (Phương tiện) + で + 行きます / 来ます (de ikimasu / kimasu)',
            'meaning' => 'Đi / Đến bằng phương tiện gì',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-de-ikimasu/',
            'example' => '電車で会社へ行きます。(Densha de kaisha e ikimasu - Tôi đi đến công ty bằng tàu điện.)',
            'note' => 'Cấu trúc: [Phương tiện] + で + V. Nếu đi bộ: 歩いて行きます (aruite ikimasu, không dùng trợ từ で).'
        ],
        [
            'stt' => 11,
            'level' => 'N5',
            'pattern' => 'N (Người) + と + V (to V)',
            'meaning' => 'Làm việc gì cùng với ai (Trợ từ cùng nhau / với)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-to-v/',
            'example' => '家族と日本へ来ました。(Kazoku to Nihon e kimashita - Tôi đã sang Nhật cùng với gia đình.)',
            'note' => 'Cấu trúc: [N người/động vật] + と + V. Nếu làm một mình: 一人で (hitori de).'
        ],
        [
            'stt' => 12,
            'level' => 'N5',
            'pattern' => 'N (Thời gian) + に + V (ni V)',
            'meaning' => 'Làm gì vào lúc [Thời gian cụ thể]',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-ni-v/',
            'example' => '毎朝6時半に起きます。(Maiasa roku-ji han ni okimasu - Mỗi sáng tôi thức dậy vào lúc 6 giờ rưỡi.)',
            'note' => 'Dùng に với mốc thời gian có số cụ thể (giờ, ngày, tháng, năm). Không dùng に với 今日, 明日, 毎日, 今.'
        ],
        [
            'stt' => 13,
            'level' => 'N5',
            'pattern' => '～から～まで (~ kara ~ made)',
            'meaning' => 'Từ ... đến ... (Thời gian / Không gian)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-kara-made/',
            'example' => '9時から5時まで働きます。(Ku-ji kara go-ji made hatarakimasu - Tôi làm việc từ 9 giờ đến 5 giờ.)',
            'note' => 'Cấu trúc: [Mốc bắt đầu] + から + [Mốc kết thúc] + まで. Có thể dùng độc lập từng từ から hoặc まで.'
        ],
        [
            'stt' => 14,
            'level' => 'N5',
            'pattern' => 'N (Tân ngữ) + を + V (o V)',
            'meaning' => 'Làm hành động gì đối với N (Trợ từ tân ngữ trực tiếp)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-o-v/',
            'example' => '毎朝パンを食べます。(Maiasa pan o tabemasu - Mỗi sáng tôi ăn bánh mì.) / 水を飲みます。(Mizu o nomimasu - Uống nước.)',
            'note' => 'Trợ từ を phát âm là "o", đứng sau đối tượng chịu sự tác động trực tiếp của ngoại động từ.'
        ],
        [
            'stt' => 15,
            'level' => 'N5',
            'pattern' => 'N (Địa điểm) + で + V (de V)',
            'meaning' => 'Làm việc gì tại / ở địa điểm nào',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-de-v/',
            'example' => '図書館で本を読みます。(Toshokan de hon o yomimasu - Đọc sách ở thư viện.)',
            'note' => 'Phân biệt: [Địa điểm] + で (nơi diễn ra hành động) vs [Địa điểm] + に (nơi tồn tại hoặc điểm đến).'
        ],
        [
            'stt' => 16,
            'level' => 'N5',
            'pattern' => 'V-ませんか (V-masen ka)',
            'meaning' => 'Bạn cùng làm ... với tôi nhé? (Lời mời mọc lịch sự)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-masen-ka/',
            'example' => 'いっしょにお茶を飲みませんか。(Isshoni ocha o nomimasen ka - Bạn cùng uống trà với tôi nhé?)',
            'note' => 'Cấu trúc: V-bỏ masu + ませんか. Thể hiện lời mời lịch sự, tôn trọng và thăm dò ý kiến của đối phương.'
        ],
        [
            'stt' => 17,
            'level' => 'N5',
            'pattern' => 'V-ましょう (V-mashou)',
            'meaning' => 'Cùng làm ... nhé! / Hãy cùng làm ... nào! (Đề nghị / Rủ rê)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-mashou/',
            'example' => 'ちょっと休みましょう。(Chotto yasumimashou - Chúng ta nghỉ giải lao một chút nào.)',
            'note' => 'Cấu trúc: V-bỏ masu + ましょう. Dùng khi chủ động đề nghị hoặc hưởng ứng lời mời của người khác.'
        ],
        [
            'stt' => 18,
            'level' => 'N5',
            'pattern' => 'N (Công cụ/Ngôn ngữ) + で + V (de V)',
            'meaning' => 'Làm gì bằng công cụ / phương tiện / ngôn ngữ gì',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-de-cong-cu/',
            'example' => '箸でご飯を食べます。(Hashi de gohan o tabemasu - Ăn cơm bằng đũa.) / 日本語でレポートを書きます。(Nihongo de repooto o kakimasu - Viết báo cáo bằng tiếng Nhật.)',
            'note' => 'Trợ từ で chỉ công cụ, dụng cụ hoặc phương thức được dùng để tiến hành hành động.'
        ],
        [
            'stt' => 19,
            'level' => 'N5',
            'pattern' => 'N (Người nhận) + に + N (Vật) + を + あげます (ni ... o agemasu)',
            'meaning' => 'Tặng / Đưa / Cho ai cái gì',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-agemasu/',
            'example' => '私は母に花をあげました。(Watashi wa haha ni hana o agemashita - Tôi đã tặng hoa cho mẹ.)',
            'note' => 'Người nhận đi với trợ từ に. Tuyệt đối không dùng あげます khi người nhận là chính mình (người khác tặng mình dùng くれます).'
        ],
        [
            'stt' => 20,
            'level' => 'N5',
            'pattern' => 'N (Người cho) + に / から + N (Vật) + を + もらいます (ni/kara ... o moraimasu)',
            'meaning' => 'Nhận cái gì từ ai',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-moraimasu/',
            'example' => '私は友達に誕生日プレゼントをもらいました。(Watashi wa tomodachi ni tanjoubi purezento o moraimashita - Tôi đã nhận quà sinh nhật từ bạn bè.)',
            'note' => 'Người cho đi với trợ từ に hoặc から. Nếu đối tượng cho là cơ quan/tổ chức thì bắt buộc dùng から.'
        ],
        [
            'stt' => 21,
            'level' => 'N5',
            'pattern' => 'もう + V-ました / まだ + V-ていません (mou ... mashita / mada ... te imasen)',
            'meaning' => 'Đã làm ... rồi / Vẫn chưa làm ...',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-mou-mada/',
            'example' => 'もう宿題をしましたか。いいえ、まだです。(Mou shukudai o shimashita ka. Iie, mada desu - Bạn đã làm bài tập chưa? Chưa, tôi chưa làm.)',
            'note' => 'Trả lời phủ định cho câu hỏi "もう～ましたか" là "いいえ、まだです" hoặc "いいえ、まだ～ていません" (không dùng V-ませんでした).'
        ],
        [
            'stt' => 22,
            'level' => 'N5',
            'pattern' => 'Tính từ đuôi い: ～いです / ～くないです / ～かったです / ～くなかったです (Adj-i)',
            'meaning' => 'Tính từ đuôi -i ở các dạng Khẳng định, Phủ định, Quá khứ',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-tinh-tu-i/',
            'example' => '昨日はとても暑かったです。(Kinou wa totemo atsukatta desu - Hôm qua trời đã rất nóng.) / この本は高くないです。(Kono hon wa takakunai desu - Cuốn sách này không đắt.)',
            'note' => 'Khẳng định: [A-i] です; Phủ định: [A-bỏ i + くない] です; Quá khứ: [A-bỏ i + かった] です; Phủ định QK: [A-bỏ i + くなかった] です. Trường hợp đặc biệt: いい -> よくありません -> よかったです.'
        ],
        [
            'stt' => 23,
            'level' => 'N5',
            'pattern' => 'Tính từ đuôi な: ～です / ～じゃありません / ～でした / ～じゃありませんでした (Adj-na)',
            'meaning' => 'Tính từ đuôi -na ở các dạng Khẳng định, Phủ định, Quá khứ',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-tinh-tu-na/',
            'example' => 'この町はとても静かです。(Kono machi wa totemo shizuka desu - Thị trấn này rất yên tĩnh.) / 昨日は暇じゃありませんでした。(Kinou wa hima ja arimasendeshita - Hôm qua tôi không rảnh.)',
            'note' => 'Khi làm vị ngữ: chia giống hệt danh từ. Khi bổ nghĩa cho danh từ đứng sau: [A-na] な + [Danh từ] (e.g. 有名な町 - thành phố nổi tiếng).'
        ],
        [
            'stt' => 24,
            'level' => 'N5',
            'pattern' => 'N1 は N2 が [Tính từ] です (N1 wa N2 ga [Adj] desu)',
            'meaning' => 'N1 thì N2 như thế nào (Thích/Ghét/Giỏi/Kém/Sở hữu)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-wa-ga-tinh-tu/',
            'example' => '私は日本料理が好きです。(Watashi wa nihon ryouri ga suki desu - Tôi thích món ăn Nhật.) / 彼は歌が上手です。(Kare wa uta ga jouzu desu - Anh ấy hát giỏi.)',
            'note' => 'Các tính từ/động từ chỉ cảm xúc, sở thích, năng lực (好き, 嫌い, 上手, 下手, 欲しい, 分かります) đi với trợ từ が cho đối tượng.'
        ],
        [
            'stt' => 25,
            'level' => 'N5',
            'pattern' => 'N (Địa điểm) + に + N + が + あります / います (ni ... ga arimasu/imasu)',
            'meaning' => 'Ở [Địa điểm] có [Đồ vật / Người / Động vật]',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-arimasu-imasu/',
            'example' => '部屋に机があります。(Heya ni tsukue ga arimasu - Trong phòng có cái bàn.) / 公園に子供がいます。(Kouen ni kodomo ga imasu - Trong công viên có trẻ con.)',
            'note' => 'あります dùng cho đồ vật, đồ vật vô tri, thực vật. います dùng cho con người và động vật có thể chuyển động.'
        ],
        [
            'stt' => 26,
            'level' => 'N5',
            'pattern' => 'N (Số lượng) + V (Từ chỉ số lượng trong câu)',
            'meaning' => 'Cách dùng từ chỉ số lượng / số đếm trong câu',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-so-luong-tu/',
            'example' => 'りんごを三つ買いました。(Ringo o mittsu kaimashita - Tôi đã mua 3 quả táo.) / 教室に学生が10人います。(Kyoushitsu ni gakusei ga juu-nin imasu - Trong lớp có 10 học sinh.)',
            'note' => 'Từ chỉ số lượng thường đặt trực tiếp ngay trước động từ mà nó bổ nghĩa, không cần thêm trợ từ ở giữa.'
        ],
        [
            'stt' => 27,
            'level' => 'N5',
            'pattern' => 'N1 は N2 より [Tính từ] です (N1 wa N2 yori [Adj] desu)',
            'meaning' => 'N1 thì [Tính từ] hơn N2 (So sánh hơn)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-so-sanh-hon/',
            'example' => '新幹線はバスより速いです。(Shinkansen wa basu yori hayai desu - Tàu Shinkansen nhanh hơn xe buýt.)',
            'note' => 'Cấu trúc so sánh hơn giữa 2 đối tượng. N2 đứng trước より là đối tượng được đem ra làm mốc so sánh.'
        ],
        [
            'stt' => 28,
            'level' => 'N5',
            'pattern' => 'N1 と N2 と どちらが [Tính từ] ですか (N1 to N2 to dochira ga [Adj] desu ka)',
            'meaning' => 'Giữa N1 và N2 thì cái nào [Tính từ] hơn? (So sánh lựa chọn)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-so-sanh-lua-chon/',
            'example' => '夏と冬とどちらが好きですか。冬のほうが好きです。(Natsu to fuyu to dochira ga suki desu ka. Fuyu no hou ga suki desu - Mùa hè và mùa đông bạn thích mùa nào hơn? Tôi thích mùa đông hơn.)',
            'note' => 'Câu trả lời chuẩn luôn có dạng: [N] のほうが [Tính từ] です (N thì ... hơn).'
        ],
        [
            'stt' => 29,
            'level' => 'N5',
            'pattern' => '[Nhóm/Phạm vi] の中で [N] が 一番 [Tính từ] です (no naka de ... ga ichiban [Adj] desu)',
            'meaning' => 'Trong [Phạm vi] thì [N] là [Tính từ] nhất (So sánh nhất)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-so-sanh-nhat/',
            'example' => 'スポーツの中でサッカーが一番面白いです。(Supootsu no naka de sakkaa ga ichiban omoshiroi desu - Trong các môn thể thao thì bóng đá là thú vị nhất.)',
            'note' => '一番 (ichiban) nghĩa là số một / nhất. Mẫu câu hỏi: [Phạm vi] の中で 何 / だれ / どこ / いつ が一番...ですか.'
        ],
        [
            'stt' => 30,
            'level' => 'N5',
            'pattern' => 'N が 欲しいです (N ga hoshii desu)',
            'meaning' => 'Tôi muốn có [Đồ vật / Danh từ]',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-ga-hoshii/',
            'example' => '私は新しいスマートフォンが欲しいです。(Watashi wa atarashii sumaatofon ga hoshii desu - Tôi muốn có một chiếc điện thoại mới.)',
            'note' => '欲しい là tính từ đuôi い, đối tượng mong muốn đi kèm trợ từ が. Chỉ dùng cho mong muốn của ngôi thứ nhất hoặc câu hỏi cho người nghe.'
        ],
        [
            'stt' => 31,
            'level' => 'N5',
            'pattern' => 'V-たいです / V-たくないです (V-tai desu / V-takunai desu)',
            'meaning' => 'Muốn làm gì / Không muốn làm gì (Mong muốn làm hành động)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-tai/',
            'example' => '日本へ旅行に行きたいです。(Nihon e ryokou ni ikitai desu - Tôi muốn đi du lịch Nhật Bản.) / 今日は何もしたくないです。(Kyou wa nani mo shitakunai desu - Hôm nay tôi chẳng muốn làm gì cả.)',
            'note' => 'Cách chia: V-bỏ masu + たいです. Trợ từ を có thể đổi thành が trước たい (e.g. お茶が飲みたいです). Chia đuôi như tính từ đuôi い.'
        ],
        [
            'stt' => 32,
            'level' => 'N5',
            'pattern' => 'N (Địa điểm) + へ + V (bỏ masu) / N + に行きます (e ... ni ikimasu)',
            'meaning' => 'Đi / Đến [Địa điểm] để làm [Mục đích]',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-e-ni-ikimasu/',
            'example' => 'スーパーへ買い物に行きます。(Suupaa e kaimono ni ikimasu - Đi siêu thị để mua đồ.) / 友達に会いに行きます。(Tomodachi ni ai ni ikimasu - Đi gặp bạn bè.)',
            'note' => 'Cấu trúc: [Địa điểm] + へ + [Động từ bỏ masu / Danh từ hành động] + に + 行きます / 来ます / 帰ります.'
        ],
        [
            'stt' => 33,
            'level' => 'N5',
            'pattern' => 'V-てください (V-te kudasai)',
            'meaning' => 'Hãy làm gì / Xin vui lòng làm gì (Yêu cầu, nhờ vả lịch sự)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-te-kudasai/',
            'example' => 'ここに住所と名前を書いてください。(Koko ni juusho to namae o kaite kudasai - Xin vui lòng viết địa chỉ và tên vào đây.)',
            'note' => 'Cấu trúc: Động từ thể て + ください. Dùng để chỉ dẫn, nhờ vả hoặc đề nghị người khác làm việc gì đó một cách lịch sự.'
        ],
        [
            'stt' => 34,
            'level' => 'N5',
            'pattern' => 'V-てもいいです (V-te mo ii desu)',
            'meaning' => 'Được phép làm gì / Làm ... có được không? (Cho phép / Xin phép)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-te-mo-ii/',
            'example' => 'ここで写真を撮ってもいいですか。(Koko de shashin o totte mo ii desu ka - Tôi có thể chụp ảnh ở đây được không?)',
            'note' => 'Cấu trúc: Động từ thể て + もいいです. Dạng câu hỏi "～てもいいですか" dùng khi xin phép làm điều gì.'
        ],
        [
            'stt' => 35,
            'level' => 'N5',
            'pattern' => 'V-てはいけません (V-te wa ikemasen)',
            'meaning' => 'Không được làm gì (Cấm đoán)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-te-wa-ikemasen/',
            'example' => 'ここでタバコを吸ってはいけません。(Koko de tabako o sutte wa ikemasen - Không được hút thuốc ở đây.)',
            'note' => 'Cấu trúc: Động từ thể て + は + いけません. Biểu thị quy định, điều cấm theo quy định hoặc lời răn đe.'
        ],
        [
            'stt' => 36,
            'level' => 'N5',
            'pattern' => 'V-ています (V-te imasu)',
            'meaning' => 'Đang làm gì (Tiếp diễn) / Trạng thái kết quả kéo dài / Nghề nghiệp',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-te-imasu/',
            'example' => '今本を読んでいます。(Ima hon o yonde imasu - Bây giờ tôi đang đọc sách.) / 私はハノイに住んでいます。(Watashi wa Hanoi ni sunde imasu - Tôi đang sống ở Hà Nội.)',
            'note' => '3 cách dùng: 1) Hành động đang diễn ra tại thời điểm nói; 2) Trạng thái kết quả (結婚しています, 持っています, 知っています); 3) Thói quen/nghề nghiệp.'
        ],
        [
            'stt' => 37,
            'level' => 'N5',
            'pattern' => 'V1-て, V2-て, V3-ます (V-te nối câu)',
            'meaning' => 'Làm V1 rồi làm V2 rồi làm V3 (Nối hành động theo thứ tự thời gian)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-te-noi-cau/',
            'example' => '朝起きて、シャワーを浴びて、学校へ行きます。(Asa okite, shawaa o abite, gakkou e ikimasu - Buổi sáng thức dậy, tắm vòi sen rồi đi đến trường.)',
            'note' => 'Dùng thể て để liên kết các hành động theo đúng trình tự trước sau. Thì của toàn bộ câu do động từ cuối cùng quyết định.'
        ],
        [
            'stt' => 38,
            'level' => 'N5',
            'pattern' => 'V1-てから, V2-ます (V1-te kara, V2-masu)',
            'meaning' => 'Sau khi làm V1 xong thì làm V2',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-te-kara/',
            'example' => '手を洗ってから、食事をします。(Te o aratte kara, shokuji o shimasu - Sau khi rửa tay xong thì ăn cơm.)',
            'note' => 'Nhấn mạnh hành động V1 phải hoàn tất xong xuôi trước rồi mới bắt đầu hành động V2.'
        ],
        [
            'stt' => 39,
            'level' => 'N5',
            'pattern' => 'V-ないでください (V-naide kudasai)',
            'meaning' => 'Xin đừng làm gì (Yêu cầu phủ định lịch sự)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-naide-kudasai/',
            'example' => 'ここに車を止めないでください。(Koko ni kuruma o tomenaide kudasai - Xin đừng đỗ xe ở đây.)',
            'note' => 'Cấu trúc: Động từ thể ない + でください. Dùng để yêu cầu người khác không làm việc gì đó.'
        ],
        [
            'stt' => 40,
            'level' => 'N5',
            'pattern' => 'V-なければなりません (V-nakereba narimasen)',
            'meaning' => 'Phải làm gì / Bắt buộc phải làm (Nghĩa vụ)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-nakereba-narimasen/',
            'example' => '明日テストがありますから、勉強しなければなりません。(Ashita tesuto ga arimasu kara, benkyou shinakereba narimasen - Vì ngày mai có bài thi nên tôi phải học bài.)',
            'note' => 'Cấu trúc: Động từ thể ない (bỏ い) + ければなりません. Thể hiện sự cần thiết, bổn phận bắt buộc phải thực hiện.'
        ],
        [
            'stt' => 41,
            'level' => 'N5',
            'pattern' => 'V-なくてもいいです (V-nakutemo ii desu)',
            'meaning' => 'Không cần phải làm gì / Không làm cũng không sao',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-nakutemo-ii/',
            'example' => '明日は日曜日ですから、早く起きなくてもいいです。(Ashita wa nichiyoubi desu kara, hayaku okinakutemo ii desu - Vì ngày mai là chủ nhật nên không cần phải dậy sớm.)',
            'note' => 'Cấu trúc: Động từ thể ない (bỏ い) + くてもいいです. Biểu thị việc không nhất thiết phải làm.'
        ],
        [
            'stt' => 42,
            'level' => 'N5',
            'pattern' => 'V (Thể từ điển) + ことができます (V-jisho koto ga dekimasu)',
            'meaning' => 'Có thể làm gì / Biết làm gì (Khả năng & điều kiện)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-koto-ga-dekimasu/',
            'example' => '私は日本語を話すことができます。(Watashi wa nihongo o hanasu koto ga dekimasu - Tôi có thể nói được tiếng Nhật.) / このホテルで両替ができます。(Kono hoteru de ryougae ga dekimasu - Có thể đổi tiền ở khách sạn này.)',
            'note' => 'Cấu trúc: [Động từ thể từ điển] + ことができます. Danh từ hóa động từ bằng こと để diễn tả năng lực hoặc điều kiện cho phép.'
        ],
        [
            'stt' => 43,
            'level' => 'N5',
            'pattern' => 'V (Thể từ điển) / N の + 前に (mae ni)',
            'meaning' => 'Trước khi làm gì / Trước một sự kiện/thời điểm',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-mae-ni/',
            'example' => '寝る前に日記を書きます。(Neru mae ni nikki o kakimasu - Trước khi đi ngủ tôi viết nhật ký.) / 食事の前に手を洗います。(Shokuji no mae ni te o araimasu - Rửa tay trước bữa ăn.)',
            'note' => 'Động từ trước 前に luôn ở thể nguyên dạng (Thể từ điển), bất kể câu ở thì hiện tại hay quá khứ.'
        ],
        [
            'stt' => 44,
            'level' => 'N5',
            'pattern' => 'V-たことがあります (V-ta koto ga arimasu)',
            'meaning' => 'Đã từng làm gì (Kinh nghiệm, trải nghiệm trong quá khứ)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-ta-koto-ga-arimasu/',
            'example' => '日本へ行ったことがあります。(Nihon e itta koto ga arimasu - Tôi đã từng đi Nhật Bản.) / 寿司を食べたことがあります。(Sushi o tabeta koto ga arimasu - Tôi đã từng ăn sushi.)',
            'note' => 'Cấu trúc: Động từ thể た + ことがあります. Dùng để nói về trải nghiệm từng có. Phủ định: ～たことがありません (chưa từng làm bao giờ).'
        ],
        [
            'stt' => 45,
            'level' => 'N5',
            'pattern' => 'V1-たり, V2-たりします (V-tari, V-tari shimasu)',
            'meaning' => 'Lúc thì làm V1, lúc thì làm V2 (Liệt kê hành động tiêu biểu)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-v-tari-v-tari/',
            'example' => '休みの日は本を読んだり、音楽を聞いたりします。(Yasumi no hi wa hon o yondari, ongaku o kiitari shimasu - Ngày nghỉ tôi lúc thì đọc sách, lúc thì nghe nhạc.)',
            'note' => 'Cấu trúc: Động từ thể た + り. Dùng để liệt kê vài hành động tiêu biểu đại diện, không cần quan tâm thứ tự trước sau.'
        ],
        [
            'stt' => 46,
            'level' => 'N5',
            'pattern' => '[Tính từ/N] + になります / くなります (ni narimasu / ku narimasu)',
            'meaning' => 'Trở nên / Trở thành ... (Biến đổi trạng thái)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-ni-narimasu/',
            'example' => '寒くなりました。(Samuku narimashita - Trời đã trở nên lạnh hơn.) / 来年医者になります。(Rainen isha ni narimasu - Sang năm tôi sẽ trở thành bác sĩ.)',
            'note' => 'Tính từ -i: bỏ い + くなります. Tính từ -na & Danh từ: + になります.'
        ],
        [
            'stt' => 47,
            'level' => 'N5',
            'pattern' => 'V (Thể thông thường) + と思います (to omoimasu)',
            'meaning' => 'Tôi nghĩ rằng ... / Dự đoán rằng ... (Ý kiến, phỏng đoán)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-to-omoimasu/',
            'example' => '明日は雨が降ると思います。(Ashita wa ame ga furu to omoimasu - Tôi nghĩ rằng ngày mai trời sẽ mưa.)',
            'note' => 'Mệnh đề trước と chia ở thể thông thường (Futsuukei). Dùng để bày tỏ quan điểm, ý kiến cá nhân hoặc dự đoán.'
        ],
        [
            'stt' => 48,
            'level' => 'N5',
            'pattern' => 'V (Thể thông thường) + と言いました (to iimashita)',
            'meaning' => 'Đã nói rằng ... (Trích dẫn gián tiếp hoặc trực tiếp)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-to-iimashita/',
            'example' => '田中さんは「明日休みます」と言いました。(Tanaka-san wa \"Ashita yasumimasu\" to iimashita - Anh Tanaka đã nói rằng \"Ngày mai tôi nghỉ\".)',
            'note' => 'Trích dẫn trực tiếp: để trong ngoặc kép 「...」; Trích dẫn gián tiếp: đổi sang thể thông thường + と言いました.'
        ],
        [
            'stt' => 49,
            'level' => 'N5',
            'pattern' => '～でしょう / ～だろう (deshou / darou)',
            'meaning' => 'Có lẽ là ... / ... phải không? (Phỏng đoán / Xác nhận)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-deshou/',
            'example' => '明日は天気がいいでしょう。(Ashita wa tenki ga ii deshou - Ngày mai có lẽ thời tiết sẽ đẹp.)',
            'note' => 'Đi với thể thông thường (Tính từ -na và Danh từ bỏ だ). Đọc lên giọng cuối câu dùng để hỏi xác nhận ý kiến người nghe.'
        ],
        [
            'stt' => 50,
            'level' => 'N5',
            'pattern' => '[Mệnh đề] + から / ので (kara / node)',
            'meaning' => 'Bởi vì ... nên ... (Nguyên nhân, lý do)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-kara-node/',
            'example' => '時間がありませんから、急ぎましょう。(Jikan ga arimasen kara, isogimashou - Vì không có thời gian nên hãy nhanh lên nào.)',
            'note' => 'から thể hiện quan điểm chủ quan của người nói. ので mang tính khách quan, trang trọng và lịch sự hơn.'
        ],
        [
            'stt' => 51,
            'level' => 'N5',
            'pattern' => '～けど / ～が (kedo / ga)',
            'meaning' => '... nhưng mà ... (Nối 2 vế câu tương phản)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-kedo-ga/',
            'example' => '日本の料理はおいしいですが、高いです。(Nihon no ryouri wa oishii desu ga, takai desu - Món ăn Nhật ngon nhưng đắt.)',
            'note' => 'が dùng trong văn viết và giao tiếp trang trọng. けど thân mật hơn, chủ yếu dùng trong hội thoại văn nói hàng ngày.'
        ],
        [
            'stt' => 52,
            'level' => 'N5',
            'pattern' => 'Trợ từ cuối câu: ～ね / ～よ (ne / yo)',
            'meaning' => '～ね: nhé/nhỉ (đồng cảm, xác nhận); ～よ: đấy/nhé (nhấn mạnh thông tin mới)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n5-ne-yo/',
            'example' => '今日はいい天気ですね。(Kyou wa ii tenki desu ne - Hôm nay thời tiết đẹp nhỉ.) / この料理はとてもおいしいですよ。(Kono ryouri wa totemo oishii desu yo - Món này ngon lắm đấy nhé.)',
            'note' => 'ね dùng khi người nói nghĩ người nghe cũng cùng quan điểm. よ dùng khi người nói muốn thông báo hoặc nhấn mạnh điều người nghe chưa biết.'
        ]
    ];

    $n4Items = [
        [
            'stt' => 1,
            'level' => 'N4',
            'pattern' => '～んです / ～のです (~ ndesu / ~ no desu)',
            'meaning' => 'Vì là, giải thích nguyên nhân, lý do, nhấn mạnh tình huống',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-ndesu/',
            'example' => '頭が痛いんです。(Atama ga itai ndesu - Vì tôi bị đau đầu.)',
            'note' => 'Động từ/Tính từ thể thông thường + んです. Tính từ -na và Danh từ + なんです.'
        ],
        [
            'stt' => 2,
            'level' => 'N4',
            'pattern' => 'V-ていただけませんか (V-te itadakemasen ka)',
            'meaning' => 'Làm ơn ... giúp tôi có được không? (Nhờ vả rất lịch sự)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-te-itadakemasen-ka/',
            'example' => '日本語を教えていただけませんか。(Nihongo o oshiete itadakemasen ka - Làm ơn dạy tiếng Nhật cho tôi có được không ạ?)',
            'note' => 'Động từ thể て + いただけませんか. Mức độ lịch sự cao hơn V-てください rất nhiều.'
        ],
        [
            'stt' => 3,
            'level' => 'N4',
            'pattern' => '～たら (~ tara)',
            'meaning' => 'Nếu ..., Sau khi ... (Điều kiện giả định & thời gian)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-tara/',
            'example' => '雨が降ったら、出かけません。(Ame ga futtara, dekakemasen - Nếu trời mưa tôi sẽ không ra ngoài.) / 駅に着いたら、電話してください。(Eki ni tsuitara, denwa shite kudasai - Sau khi đến ga hãy gọi cho tôi.)',
            'note' => 'Động từ thể Quá khứ ngắn (thể た) + ら.'
        ],
        [
            'stt' => 4,
            'level' => 'N4',
            'pattern' => '～ば (~ ba)',
            'meaning' => 'Nếu ... (Điều kiện thể giả định - Ba-kei)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-ba/',
            'example' => '安ければ、買います。(Yasukereba, kaimasu - Nếu rẻ thì tôi sẽ mua.) / 勉強すれば、合格できます。(Benkyou sureba, goukaku dekimasu - Nếu học thì có thể đỗ.)',
            'note' => 'Động từ nhóm 1: đổi âm u -> e + ば; nhóm 2: bỏ ru + れば; nhóm 3: すれば, くれば.'
        ],
        [
            'stt' => 5,
            'level' => 'N4',
            'pattern' => '～なら (~ nara)',
            'meaning' => 'Nếu là ... / Nếu nói về ... (Tiếp nhận chủ đề và đưa ra lời khuyên)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-nara/',
            'example' => '日本料理なら、寿司が一番好きです。(Nihon ryouri nara, sushi ga ichiban suki desu - Nếu là món Nhật thì tôi thích sushi nhất.)',
            'note' => 'Danh từ / Tính từ / Thể thông thường + なら. Dùng để đưa ra ý kiến, lời khuyên dựa trên chủ đề người nghe vừa nói.'
        ],
        [
            'stt' => 6,
            'level' => 'N4',
            'pattern' => '～ても / ～でも (~ temo / ~ demo)',
            'meaning' => 'Dù ... nhưng ..., Cho dù có ... đi nữa',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-temo/',
            'example' => '高くても、買いたいです。(Takakutemo, kaitai desu - Dù đắt nhưng tôi vẫn muốn mua.)',
            'note' => 'Động từ thể て + も; Tính từ -i bỏ い + くても; Tính từ -na & Danh từ + でも.'
        ],
        [
            'stt' => 7,
            'level' => 'N4',
            'pattern' => '～てあげる / ～てもらう / ～てくれる (~ te ageru / te morau / te kureru)',
            'meaning' => 'Làm giúp cho ai / Được ai làm cho / Ai làm giúp cho mình',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-cho-nhan/',
            'example' => '友達が荷物を持ってくれました。(Tomodachi ga nimotsu o motte kuremashita - Bạn tôi đã mang hành lý giúp tôi.)',
            'note' => 'Bộ 3 động từ cho nhận hành động cực kỳ quan trọng trong giao tiếp tiếng Nhật.'
        ],
        [
            'stt' => 8,
            'level' => 'N4',
            'pattern' => '～そうです (Trực quan: có vẻ / Nghe nói)',
            'meaning' => 'Có vẻ sắp ... (Trực quan) / Nghe nói rằng ... (Truyền đạt)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-sou-desu/',
            'example' => '今にも雨が降りそうです。(Ima nimo ame ga furisou desu - Có vẻ trời sắp mưa đến nơi rồi.) / 明日は雨だそうです。(Ashita wa ame da sou desu - Nghe nói ngày mai trời mưa.)',
            'note' => 'V-bỏ masu + そうです: có vẻ; Thể thông thường + そうです: nghe nói.'
        ],
        [
            'stt' => 9,
            'level' => 'N4',
            'pattern' => '～すぎる (~ sugiru)',
            'meaning' => 'Quá mức ... (Vượt quá giới hạn bình thường)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-sugiru/',
            'example' => '昨日お酒を飲みすぎました。(Kinou osake o nomisugimashita - Hôm qua tôi đã uống quá nhiều rượu.)',
            'note' => 'V-bỏ masu / Tính từ -i bỏ い / Tính từ -na + すぎる. Chia như động từ nhóm 2.'
        ],
        [
            'stt' => 10,
            'level' => 'N4',
            'pattern' => '～やすい / ～にくい (~ yasui / ~ nikui)',
            'meaning' => 'Dễ làm gì / Khó làm gì',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-yasui-nikui/',
            'example' => 'この薬は飲みやすいです。(Kono kusuri wa nomiyasui desu - Thuốc này rất dễ uống.)',
            'note' => 'V-bỏ masu + やすい / にくい. Đóng vai trò như một tính từ đuôi い.'
        ],
        [
            'stt' => 11,
            'level' => 'N4',
            'pattern' => '～てしまう (~ te shimau)',
            'meaning' => 'Đã lỡ làm gì (Tiếc nuối) / Làm xong hoàn tất toàn bộ',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-te-shimau/',
            'example' => 'パスポートを忘れてしまいました。(Pasupooto o wasurete shimaimashita - Tôi đã lỡ quên hộ chiếu mất rồi.)',
            'note' => 'Động từ thể て + しまう. Văn nói thân mật thường rút gọn thành ちゃう / じゃう.'
        ],
        [
            'stt' => 12,
            'level' => 'N4',
            'pattern' => '～受身動詞 (Động từ bị động: ～れる / ～られる)',
            'meaning' => 'Bị / Được làm gì (Thể bị động Ukemi)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-bi-dong/',
            'example' => '私は先生に褒められました。(Watashi wa sensei ni homeraremashita - Tôi đã được thầy giáo khen ngợi.)',
            'note' => 'Nhóm 1: đổi âm u -> a + れる; Nhóm 2: bỏ ru + られる; Nhóm 3: される, こられる.'
        ],
        [
            'stt' => 13,
            'level' => 'N4',
            'pattern' => '～使役動詞 (Động từ sai khiến: ～せる / ～させる)',
            'meaning' => 'Bắt / Cho phép ai làm gì (Thể sai khiến Shieki)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-sai-khien/',
            'example' => '先生は生徒に本を読ませました。(Sensei wa seito ni hon o yomasemashita - Thầy giáo bắt học sinh đọc sách.)',
            'note' => 'Nhóm 1: đổi âm u -> a + せる; Nhóm 2: bỏ ru + させる; Nhóm 3: させる, こさせる.'
        ],
        [
            'stt' => 14,
            'level' => 'N4',
            'pattern' => '～ように / ～ために (~ you ni / ~ tame ni)',
            'meaning' => 'Để ... (Chỉ mục đích)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-you-ni-tame-ni/',
            'example' => '試験に合格できるように、毎日勉強します。(Shiken ni goukaku dekiru you ni, mainichi benkyou shimasu - Để có thể đỗ kỳ thi, mỗi ngày tôi đều học.)',
            'note' => 'ように đi với động từ không có ý chí / thể khả năng; ために đi với động từ có ý chí / danh từ の.'
        ],
        [
            'stt' => 15,
            'level' => 'N4',
            'pattern' => '～はずです (~ hazu desu)',
            'meaning' => 'Chắc chắn là ... (Phán đoán có căn cứ xác thực)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-hazu-desu/',
            'example' => '彼は今日来るはずです。(Kare wa kyou kuru hazu desu - Chắc chắn hôm nay anh ấy sẽ đến.)',
            'note' => 'Thể thông thường + はずです. Tính từ -na + なはず, Danh từ + のはず.'
        ],
        [
            'stt' => 16,
            'level' => 'N4',
            'pattern' => '～かもしれません (~ kamo shiremasen)',
            'meaning' => 'Có thể là, có lẽ là ... (Xác suất khoảng 50%)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-kamo-shiremasen/',
            'example' => '午後は雨が降るかもしれません。(Gogo wa ame ga furu kamo shiremasen - Buổi chiều có lẽ trời sẽ mưa.)',
            'note' => 'Thể thông thường (Tính từ -na và Danh từ không có だ) + かもしれません.'
        ],
        [
            'stt' => 17,
            'level' => 'N4',
            'pattern' => '～つもりです / ～予定です (~ tsumori desu / ~ yotei desu)',
            'meaning' => 'Dự định làm gì / Kế hoạch làm gì',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-tsumori-yotei/',
            'example' => '来年日本へ行くつもりです。(Rainen Nihon e iku tsumori desu - Sang năm tôi dự định sẽ đi Nhật.)',
            'note' => 'V (Thể từ điển / Thể ない) + つもりです. V (Thể từ điển) / N の + 予定です.'
        ],
        [
            'stt' => 18,
            'level' => 'N4',
            'pattern' => '～まま (~ mama)',
            'meaning' => 'Để nguyên trạng thái ..., Cứ giữ nguyên ...',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n4-mama/',
            'example' => '靴を履いたまま、部屋に入ってはいけません。(Kutsu o haita mama, heya ni haitte wa ikemasen - Không được đi cả giày vào trong phòng.)',
            'note' => 'V-た / V-ない / N の + まま.'
        ]
    ];

    $n3Items = [
        [
            'stt' => 1,
            'level' => 'N3',
            'pattern' => '～わけだ (~ wake da)',
            'meaning' => 'Thảo nào, hèn chi, đương nhiên là ...',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n3-wake-da/',
            'example' => '暑いわけだ。気温が38度もある。(Atsui wake da. Kion ga sanjuuhachi-do mo aru - Thảo nào nóng thế. Nhiệt độ lên tới 38 độ.)',
            'note' => 'Thể thông thường (Tính từ -na + な, Danh từ + である/の) + わけだ.'
        ],
        [
            'stt' => 2,
            'level' => 'N3',
            'pattern' => '～わけがない (~ wake ga nai)',
            'meaning' => 'Tuyệt đối không thể nào ..., Làm sao mà ... được',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n3-wake-ga-nai/',
            'example' => 'そんな難しいこと、彼にできるわけがない。(Sonna muzukashii koto, kare ni dekiru wake ga nai - Việc khó như thế làm sao anh ấy làm được.)',
            'note' => 'Thể hiện sự phủ định mạnh mẽ dựa trên lý lẽ chắc chắn.'
        ],
        [
            'stt' => 3,
            'level' => 'N3',
            'pattern' => '～わけではない (~ wake dewa nai)',
            'meaning' => 'Không hẳn là, không có nghĩa là ... (Phủ định một phần)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n3-wake-dewa-nai/',
            'example' => '嫌いなわけではないが、あまり食べたくない。(Kirai na wake dewa nai ga, amari tabetakunai - Không hẳn là tôi ghét, nhưng tôi không muốn ăn lắm.)',
            'note' => 'Phủ định nhẹ nhàng để tránh nói tuyệt đối.'
        ],
        [
            'stt' => 4,
            'level' => 'N3',
            'pattern' => '～ことになっている (~ koto ni natte iru)',
            'meaning' => 'Được quy định là ..., Theo lịch trình/quy tắc là ...',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n3-koto-ni-natte-iru/',
            'example' => '法律で20歳未満は酒を飲んではいけないことになっている。(Houritsu de nijuusai miman wa sake o nonde wa ikenai koto ni natte iru - Theo luật quy định người dưới 20 tuổi không được uống rượu.)',
            'note' => 'Diễn tả quy tắc, quy định chung hoặc tập quán của xã hội/tổ chức.'
        ],
        [
            'stt' => 5,
            'level' => 'N3',
            'pattern' => '～に対して (~ ni taishite)',
            'meaning' => 'Đối với ... / Trái ngược với ...',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n3-ni-taishite/',
            'example' => 'お客様に対して礼儀正しくしてください。(Okyakusama ni taishite reigi tadashiku shite kudasai - Hãy lịch sự đối với khách hàng.)',
            'note' => 'N + に対して / N1 に対する N2.'
        ],
        [
            'stt' => 6,
            'level' => 'N3',
            'pattern' => '～に関して (~ ni kanshite)',
            'meaning' => 'Liên quan đến ..., Về vấn đề ...',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n3-ni-kanshite/',
            'example' => '環境問題に関して議論しました。(Kankyou mondai ni kanshite giron shimashita - Đã thảo luận về vấn đề môi trường.)',
            'note' => 'Trang trọng hơn について. N + に関して / N1 に関する N2.'
        ],
        [
            'stt' => 7,
            'level' => 'N3',
            'pattern' => '～によると / ～によれば (~ ni yoru to / ~ ni yoreba)',
            'meaning' => 'Căn cứ theo ..., Theo nguồn tin từ ...',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n3-ni-yoru-to/',
            'example' => '天気予報によると、明日は晴れるそうです。(Tenki yohou ni yoru to, ashita wa hareru sou desu - Theo dự báo thời tiết thì ngày mai trời sẽ nắng.)',
            'note' => 'Chỉ nguồn thông tin, vế sau thường kết hợp với ～そうだ / ～ということだ.'
        ],
        [
            'stt' => 8,
            'level' => 'N3',
            'pattern' => '～おかげで / ～せいで (~ okage de / ~ sei de)',
            'meaning' => 'Nhờ có ... (Kết quả tốt) / Tại vì ... (Kết quả xấu, trách móc)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n3-okage-sei/',
            'example' => '先生のおかげで、試験に合格しました。(Sensei no okage de, shiken ni goukaku shimashita - Nhờ có thầy giáo mà tôi đã đỗ kỳ thi.)',
            'note' => 'おかげで dùng cho kết quả tích cực, せいで dùng cho kết quả tiêu cực.'
        ],
        [
            'stt' => 9,
            'level' => 'N3',
            'pattern' => '～うちに (~ uchi ni)',
            'meaning' => 'Trong khi còn ... (Tranh thủ trước khi trạng thái thay đổi)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n3-uchi-ni/',
            'example' => '温かいうちに召し上がってください。(Atatakai uchi ni meshiagatte kudasai - Xin hãy dùng bữa trong khi món ăn còn nóng.)',
            'note' => 'V-te iru / V-nai / Adj-i / Adj-na な / N の + うちに.'
        ],
        [
            'stt' => 10,
            'level' => 'N3',
            'pattern' => '～たびに (~ tabi ni)',
            'meaning' => 'Mỗi lần ... lại ..., Cứ mỗi khi ... thì lại ...',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n3-tabi-ni/',
            'example' => 'この写真を見るたびに、家族を思い出します。(Kono shashin o miru tabi ni, kazoku o omoidashimasu - Mỗi lần xem bức ảnh này tôi lại nhớ gia đình.)',
            'note' => 'V (Thể từ điển) / N の + たびに.'
        ]
    ];

    // Load N2 Grammar from data/N2 JP Grammar.html
    $n2Items = [];
    $dataDir = $config['data_dir'] ?? (__DIR__ . '/../data');
    $n2File = $dataDir . '/N2 JP Grammar.html';

    if (is_file($n2File)) {
        $content = file_get_contents($n2File);
        $lines = explode("\n", $content);
        $stt = 1;
        foreach ($lines as $i => $line) {
            $line = trim($line);
            if ($i === 0 || empty($line)) {
                continue;
            }
            if (preg_match('/^\[(.*?)\]\((.*?)\)/', $line, $matches)) {
                $text = trim($matches[1]);
                $link = trim($matches[2]);
                $parts = explode(':', $text, 2);
                $pattern = trim($parts[0]);
                $meaning = isset($parts[1]) ? trim($parts[1]) : '';
                
                $n2Items[] = [
                    'stt' => $stt++,
                    'level' => 'N2',
                    'pattern' => $pattern,
                    'meaning' => $meaning !== '' ? $meaning : 'Ngữ pháp JLPT N2 chuyên sâu',
                    'link' => $link,
                    'example' => "Ví dụ minh họa mẫu câu: {$pattern}",
                    'note' => 'Mẫu câu ngữ pháp trung - cao cấp JLPT N2. Xem chi tiết cấu trúc kết hợp qua link hướng dẫn.'
                ];
            }
        }
    }

    $n1Items = [
        [
            'stt' => 1,
            'level' => 'N1',
            'pattern' => '～極まりない / ～極まる (~ kiwamarinai / ~ kiwamaru)',
            'meaning' => 'Vô cùng, cực kỳ ... (Đến mức tột cùng)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n1-kiwamarinai/',
            'example' => '彼の態度は失礼極まりない。(Kare no taido wa shitsurei kiwamarinai - Thái độ của anh ta vô cùng thất lễ.)',
            'note' => 'Tính từ -na (bỏ な) / Tính từ -i + 極まりない. Dùng trong văn cảnh trang trọng.'
        ],
        [
            'stt' => 2,
            'level' => 'N1',
            'pattern' => '～を皮切りに (~ o kawakiri ni)',
            'meaning' => 'Khởi đầu với ..., Bắt đầu bằng việc ...',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n1-o-kawakiri-ni/',
            'example' => '東京公演を皮切りに、全国ツアーがスタートした。(Toukyou kouen o kawakiri ni, zenkoku tsuaa ga sutaato shita - Khởi đầu với buổi diễn tại Tokyo, tour lưu diễn toàn quốc đã bắt đầu.)',
            'note' => 'N + を皮切りに(して) / を皮切りとして.'
        ],
        [
            'stt' => 3,
            'level' => 'N1',
            'pattern' => '～であれ / ～であろうと (~ de are / ~ de arou to)',
            'meaning' => 'Cho dù là ..., Dù có là ai/cái gì đi chăng nữa',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n1-de-are/',
            'example' => 'たとえ大統領であれ、法律を守らなければならない。(Tatoe daitouryou de are, houritsu o mamoranakereba naranai - Cho dù có là tổng thống thì cũng phải tuân thủ pháp luật.)',
            'note' => 'N / Từ để hỏi + であれ.'
        ],
        [
            'stt' => 4,
            'level' => 'N1',
            'pattern' => '～たるもの (~ taru mono)',
            'meaning' => 'Đã là ..., Trên cương vị là ... (Phải có tư cách xứng đáng)',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n1-taru-mono/',
            'example' => '指導者たるもの、常に冷静でなければならない。(Shidousha taru mono, tsuneni reisei de nakereba naranai - Đã là người lãnh đạo thì luôn phải giữ được sự bình tĩnh.)',
            'note' => 'N (chỉ chức vụ, nghề nghiệp, tư cách) + たるもの.'
        ],
        [
            'stt' => 5,
            'level' => 'N1',
            'pattern' => '～まじき (~ majiki)',
            'meaning' => 'Không thể chấp nhận được, Không được phép đối với ...',
            'link' => 'http://tiengnhat.minder.vn/ngu-phap-jlpt-n1-majiki/',
            'example' => 'それはプロとしてあるまじき行為だ。(Sore wa puro to shite aru majiki koui da - Đó là hành vi không thể chấp nhận được đối với một người chuyên nghiệp.)',
            'note' => 'V (Thể từ điển) + まじき + N.'
        ]
    ];

    $allLevels = [
        'N5' => [
            'level' => 'N5',
            'title' => 'JLPT N5 (Sơ Cấp 1)',
            'desc' => 'Mẫu câu căn bản, trợ từ, thể Te/Nai/Ta và giao tiếp đời sống',
            'badge' => 'Sơ cấp 1',
            'color' => 'emerald',
            'items' => $n5Items
        ],
        'N4' => [
            'level' => 'N4',
            'title' => 'JLPT N4 (Sơ Cấp 2)',
            'desc' => 'Thể bị động, sai khiến, điều kiện Tara/Ba/Nara và cho nhận',
            'badge' => 'Sơ cấp 2',
            'color' => 'teal',
            'items' => $n4Items
        ],
        'N3' => [
            'level' => 'N3',
            'title' => 'JLPT N3 (Trung Cấp)',
            'desc' => 'Ngữ pháp trung cấp, giải thích lý do, quan điểm và văn phong nhật dụng',
            'badge' => 'Trung cấp',
            'color' => 'sky',
            'items' => $n3Items
        ],
        'N2' => [
            'level' => 'N2',
            'title' => 'JLPT N2 (Trung - Cao Cấp)',
            'desc' => '500 mẫu ngữ pháp N2 chọn lọc chuẩn từ điển Minder JP Grammar',
            'badge' => '504 Mẫu',
            'color' => 'indigo',
            'items' => $n2Items
        ],
        'N1' => [
            'level' => 'N1',
            'title' => 'JLPT N1 (Cao Cấp)',
            'desc' => 'Mẫu câu nâng cao, văn phong trang trọng báo chí và nghị luận chuyên sâu',
            'badge' => 'Cao cấp',
            'color' => 'purple',
            'items' => $n1Items
        ]
    ];

    $allItems = [];
    foreach ($allLevels as $lvlKey => $lvlData) {
        foreach ($lvlData['items'] as $item) {
            $allItems[] = $item;
        }
    }

    return [
        'levels' => $allLevels,
        'all_items' => $allItems,
        'total_count' => count($allItems)
    ];
}

